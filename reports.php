<?php
require_once __DIR__ . '/config.php';
require_page_permission('reports');

function report_status_label($status) {
    return [
        'debtor' => 'بدهکار',
        'creditor' => 'بستانکار',
        'settled' => 'تسویه',
    ][$status] ?? $status;
}

$buildingId = (int)($_GET['building_id'] ?? 0);
$status = trim($_GET['status'] ?? '');
$period = trim($_GET['period'] ?? '');

$buildings = $pdo->query('SELECT id,name FROM buildings ORDER BY name')->fetchAll();

$where = [];
$params = [];
if ($buildingId > 0) {
    $where[] = 'u.building_id=?';
    $params[] = $buildingId;
}
if (in_array($status, ['debtor','creditor','settled'], true)) {
    $where[] = 'u.financial_status=?';
    $params[] = $status;
}
$unitSql = 'SELECT u.id,u.unit_no,u.status,u.financial_status,b.name AS building_name,
    COALESCE((SELECT SUM(c.amount) FROM charges c WHERE c.unit_id=u.id),0) AS total_charges,
    COALESCE((SELECT SUM(p.amount) FROM payments p JOIN charges c2 ON c2.id=p.charge_id WHERE c2.unit_id=u.id),0) AS total_paid
    FROM units u JOIN buildings b ON b.id=u.building_id';
if ($where) $unitSql .= ' WHERE ' . implode(' AND ', $where);
$unitSql .= ' ORDER BY b.name,u.unit_no';
$st = $pdo->prepare($unitSql);
$st->execute($params);
$units = $st->fetchAll();

$chargeWhere = [];
$chargeParams = [];
if ($buildingId > 0) { $chargeWhere[]='u.building_id=?'; $chargeParams[]=$buildingId; }
if ($period !== '') { $chargeWhere[]='c.period=?'; $chargeParams[]=$period; }
$chargeSql = 'SELECT c.id,c.title,c.period,c.amount,c.status,c.due_date,u.unit_no,b.name AS building_name,
    COALESCE((SELECT SUM(p.amount) FROM payments p WHERE p.charge_id=c.id),0) AS paid_amount
    FROM charges c JOIN units u ON u.id=c.unit_id JOIN buildings b ON b.id=u.building_id';
if ($chargeWhere) $chargeSql .= ' WHERE '.implode(' AND ',$chargeWhere);
$chargeSql .= ' ORDER BY c.period DESC,b.name,u.unit_no';
$st = $pdo->prepare($chargeSql);
$st->execute($chargeParams);
$charges = $st->fetchAll();

$totals = ['charges'=>0.0,'paid'=>0.0,'balance'=>0.0];
foreach ($charges as $row) {
    $totals['charges'] += (float)$row['amount'];
    $totals['paid'] += (float)$row['paid_amount'];
}
$totals['balance'] = $totals['charges'] - $totals['paid'];

$contractSql = 'SELECT c.id,c.type,c.start_date,c.end_date,c.amount,c.deposit_amount,u.unit_no,b.name AS building_name,
    CONCAT(p.first_name," ",p.last_name) AS person_name
    FROM contracts c JOIN units u ON u.id=c.unit_id JOIN buildings b ON b.id=u.building_id
    JOIN persons p ON p.id=c.person_id';
$contractParams=[];
if ($buildingId > 0) { $contractSql .= ' WHERE u.building_id=?'; $contractParams[]=$buildingId; }
$contractSql .= ' ORDER BY c.start_date DESC';
$st=$pdo->prepare($contractSql); $st->execute($contractParams); $contracts=$st->fetchAll();

page_header('گزارش‌ها');
?>
<div class="content-header mb-4">
  <div><h4 class="mb-1">گزارش‌ها</h4><p class="text-muted mb-0">وضعیت واحدها، بدهی و بستانکاری، شارژها و قراردادها.</p></div>
</div>
<div class="card mb-4"><div class="card-body">
<form method="get"><div class="row align-items-end">
<div class="col-md-4 form-group"><label>ساختمان</label><select class="form-control" name="building_id"><option value="0">همه ساختمان‌ها</option>
<?php foreach($buildings as $b): ?><option value="<?=$b['id']?>" <?=$buildingId===$b['id']?'selected':''?>><?=e($b['name'])?></option><?php endforeach; ?></select></div>
<div class="col-md-3 form-group"><label>وضعیت مالی واحد</label><select class="form-control" name="status"><option value="">همه</option><?php foreach(['debtor'=>'بدهکار','creditor'=>'بستانکار','settled'=>'تسویه'] as $k=>$v): ?><option value="<?=$k?>" <?=$status===$k?'selected':''?>><?=$v?></option><?php endforeach; ?></select></div>
<div class="col-md-3 form-group"><label>دوره شارژ</label><input class="form-control" name="period" value="<?=e($period)?>" placeholder="مثلاً 1405-01"></div>
<div class="col-md-2 form-group"><button class="btn btn-primary btn-block">فیلتر</button></div>
</div></form></div></div>

<div class="row">
<?php foreach([['کل شارژ','charges'],['پرداخت شده','paid'],['مانده','balance']] as $card): ?>
<div class="col-md-4"><div class="card"><div class="card-body"><span class="text-muted"><?=$card[0]?></span><h4><?=money($totals[$card[1]])?></h4></div></div></div>
<?php endforeach; ?>
</div>

<div class="card mt-4"><div class="card-body"><h5 class="card-title">وضعیت مالی واحدها</h5><div class="table-responsive"><table class="table"><thead><tr><th>ساختمان</th><th>واحد</th><th>وضعیت</th><th>شارژ</th><th>پرداخت</th><th>مانده</th></tr></thead><tbody>
<?php foreach($units as $u): $bal=(float)$u['total_charges']-(float)$u['total_paid']; ?>
<tr><td><?=e($u['building_name'])?></td><td><?=e($u['unit_no'])?></td><td><?=e(report_status_label($u['financial_status']))?></td><td><?=money($u['total_charges'])?></td><td><?=money($u['total_paid'])?></td><td><?=money($bal)?></td></tr>
<?php endforeach; ?></tbody></table></div></div></div>

<div class="card mt-4"><div class="card-body"><h5 class="card-title">وضعیت شارژهای صادرشده</h5><div class="table-responsive"><table class="table"><thead><tr><th>ساختمان</th><th>واحد</th><th>دوره</th><th>شارژ</th><th>مبلغ</th><th>پرداخت</th><th>مانده</th><th>وضعیت</th></tr></thead><tbody>
<?php foreach($charges as $c): $bal=max(0,(float)$c['amount']-(float)$c['paid_amount']); ?>
<tr><td><?=e($c['building_name'])?></td><td><?=e($c['unit_no'])?></td><td><?=e($c['period'])?></td><td><?=e($c['title'])?></td><td><?=money($c['amount'])?></td><td><?=money($c['paid_amount'])?></td><td><?=money($bal)?></td><td><?=e($c['status'])?></td></tr>
<?php endforeach; ?></tbody></table></div></div></div>

<div class="card mt-4"><div class="card-body"><h5 class="card-title">قراردادهای اجاره و فروش</h5><div class="table-responsive"><table class="table"><thead><tr><th>ساختمان</th><th>واحد</th><th>نوع</th><th>طرف قرارداد</th><th>شروع</th><th>پایان</th><th>مبلغ</th><th>ودیعه</th></tr></thead><tbody>
<?php foreach($contracts as $c): ?><tr><td><?=e($c['building_name'])?></td><td><?=e($c['unit_no'])?></td><td><?=e($c['type'])?></td><td><?=e(trim($c['person_name']))?></td><td><?=e($c['start_date'])?></td><td><?=e($c['end_date'] ?? '-')?></td><td><?=money($c['amount'])?></td><td><?=money($c['deposit_amount'])?></td></tr><?php endforeach; ?></tbody></table></div></div></div>
<?php page_footer(); ?>