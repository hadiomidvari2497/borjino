<?php
require_once __DIR__.'/config.php';
require_page_permission('contracts');

$types=['rent'=>'اجاره','sale'=>'فروش'];

if($_SERVER['REQUEST_METHOD']==='POST'){
    check_csrf();
    $action=post('action');
    $id=(int)post('id');

    if($action==='save'){
        require_permission('contracts',$id?'edit':'create');
        $unitId=(int)post('unit_id');
        $personId=(int)post('person_id');
        $type=post('type','rent');
        $startDate=post('start_date');
        $endDate=post('end_date')?:null;
        $amount=(float)post('amount',0);
        $deposit=(float)post('deposit_amount',0);
        $notes=trim(post('notes'))?:null;

        if(!$unitId||!$personId||!isset($types[$type])||!$startDate){
            flash('واحد، طرف قرارداد، نوع و تاریخ عقد الزامی است.');
            redirect('contracts.php'.($id?'?edit='.$id:'?new=1'));
        }
        if($type==='rent' && $amount<0){
            flash('اجاره ماهیانه نمی‌تواند منفی باشد.');
            redirect('contracts.php'.($id?'?edit='.$id:'?new=1'));
        }
        if($type==='sale' && $amount<=0){
            flash('مبلغ فروش باید بیشتر از صفر باشد.');
            redirect('contracts.php'.($id?'?edit='.$id:'?new=1'));
        }
        if($endDate && $endDate<$startDate){
            flash('تاریخ پایان نمی‌تواند قبل از تاریخ عقد باشد.');
            redirect('contracts.php'.($id?'?edit='.$id:'?new=1'));
        }

        if($type==='sale') $deposit=0;

        if($id){
            $st=$pdo->prepare('SELECT id FROM contracts WHERE id=?');
            $st->execute([$id]);
            if(!$st->fetch()){flash('قرارداد پیدا نشد.');redirect('contracts.php');}
            $pdo->prepare('UPDATE contracts SET unit_id=?,person_id=?,type=?,start_date=?,end_date=?,amount=?,deposit_amount=?,notes=? WHERE id=?')
                ->execute([$unitId,$personId,$type,$startDate,$endDate,$amount,$deposit,$notes,$id]);
            log_activity('update','contracts',$id,'ویرایش قرارداد');
            flash('قرارداد ویرایش شد.');
        }else{
            $pdo->prepare('INSERT INTO contracts(unit_id,person_id,type,start_date,end_date,amount,deposit_amount,notes) VALUES(?,?,?,?,?,?,?,?)')
                ->execute([$unitId,$personId,$type,$startDate,$endDate,$amount,$deposit,$notes]);
            $id=(int)$pdo->lastInsertId();
            log_activity('create','contracts',$id,'ایجاد قرارداد');
            flash('قرارداد ثبت شد.');
        }
        redirect('contracts.php');
    }

    if($action==='delete'){
        require_permission('contracts','delete');
        $pdo->prepare('DELETE FROM contracts WHERE id=?')->execute([$id]);
        log_activity('delete','contracts',$id,'حذف قرارداد');
        flash('قرارداد حذف شد.');
        redirect('contracts.php');
    }
}

$units=$pdo->query('SELECT u.id,u.unit_no,b.name building_name,bl.name block_name FROM units u JOIN buildings b ON b.id=u.building_id JOIN blocks bl ON bl.id=u.block_id ORDER BY b.name,bl.name,u.unit_no')->fetchAll();
$persons=$pdo->query('SELECT id,first_name,last_name,mobile FROM persons ORDER BY last_name,first_name')->fetchAll();
$edit=null;
if(isset($_GET['edit'])){
    $st=$pdo->prepare('SELECT * FROM contracts WHERE id=?');
    $st->execute([(int)$_GET['edit']]);
    $edit=$st->fetch();
    if(!$edit){flash('قرارداد پیدا نشد.');redirect('contracts.php');}
}
$rows=$pdo->query("SELECT c.*,u.unit_no,b.name building_name,bl.name block_name,CONCAT(p.first_name,' ',p.last_name) person_name FROM contracts c JOIN units u ON u.id=c.unit_id JOIN buildings b ON b.id=u.building_id JOIN blocks bl ON bl.id=u.block_id JOIN persons p ON p.id=c.person_id ORDER BY c.id DESC")->fetchAll();

page_header('قراردادها');
?>
<div class="content-header mb-4">
    <div><h4 class="mb-1">قراردادها</h4><p class="text-muted mb-0">مدیریت قراردادهای اجاره و فروش واحدها.</p></div>
    <?php if(has_permission('contracts','create')):?><a class="btn btn-primary" href="contracts.php?new=1">قرارداد جدید</a><?php endif;?>
</div>

<?php if(isset($_GET['new'])||$edit): ?>
<div class="card mb-4"><div class="card-body">
<h6 class="card-title mb-4"><?= $edit?'ویرایش قرارداد':'ثبت قرارداد جدید' ?></h6>
<form method="post" id="contractForm">
<?=csrf_field()?><input type="hidden" name="action" value="save"><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?>
<div class="row">
<div class="col-md-4 form-group"><label>واحد</label><select class="form-control" name="unit_id" required><option value="">انتخاب واحد</option><?php foreach($units as $u):?><option value="<?=$u['id']?>" <?=($edit['unit_id']??'')==$u['id']?'selected':''?>><?=e($u['building_name'].' / '.$u['block_name'].' / واحد '.$u['unit_no'])?></option><?php endforeach;?></select></div>
<div class="col-md-4 form-group"><label>عضو / طرف قرارداد</label><select class="form-control" name="person_id" required><option value="">انتخاب شخص</option><?php foreach($persons as $p):?><option value="<?=$p['id']?>" <?=($edit['person_id']??'')==$p['id']?'selected':''?>><?=e($p['first_name'].' '.$p['last_name'])?></option><?php endforeach;?></select></div>
<div class="col-md-4 form-group"><label>نوع قرارداد</label><select class="form-control" name="type" id="contractType"><option value="rent" <?=($edit['type']??'rent')==='rent'?'selected':''?>>اجاره</option><option value="sale" <?=($edit['type']??'')==='sale'?'selected':''?>>فروش</option></select></div>
<div class="col-md-4 form-group"><label>تاریخ عقد</label><input class="form-control" type="date" name="start_date" required value="<?=e($edit['start_date']??date('Y-m-d'))?>"></div>
<div class="col-md-4 form-group" id="endDateWrap"><label>تاریخ پایان</label><input class="form-control" type="date" name="end_date" value="<?=e($edit['end_date']??'')?>"></div>
<div class="col-md-4 form-group" id="amountWrap"><label id="amountLabel">اجاره ماهیانه</label><input class="form-control" type="number" min="0" step="0.01" name="amount" value="<?=e($edit['amount']??0)?>"></div>
<div class="col-md-4 form-group" id="depositWrap"><label>پیش‌پرداخت / ودیعه</label><input class="form-control" type="number" min="0" step="0.01" name="deposit_amount" value="<?=e($edit['deposit_amount']??0)?>"></div>
<div class="col-md-12 form-group"><label>توضیحات</label><textarea class="form-control" name="notes"><?=e($edit['notes']??'')?></textarea></div>
</div>
<button class="btn btn-primary">ذخیره قرارداد</button> <a class="btn btn-outline-secondary" href="contracts.php">انصراف</a>
</form></div></div>
<?php endif;?>

<div class="card"><div class="card-body"><div class="table-responsive">
<table class="table table-hover">
<thead><tr><th>واحد</th><th>طرف قرارداد</th><th>نوع</th><th>تاریخ عقد</th><th>پایان</th><th>مبلغ</th><th>ودیعه</th><th>عملیات</th></tr></thead>
<tbody>
<?php foreach($rows as $r):?>
<tr>
<td><?=e($r['building_name'].' / '.$r['block_name'].' / '.$r['unit_no'])?></td>
<td><?=e($r['person_name'])?></td>
<td><span class="badge badge-<?= $r['type']==='rent'?'info':'success' ?>"><?=e($types[$r['type']]??$r['type'])?></span></td>
<td><?=e($r['start_date'])?></td><td><?=e($r['end_date']??'-')?></td>
<td><?=money($r['amount'])?></td><td><?=money($r['deposit_amount'])?></td>
<td><?php if(has_permission('contracts','edit')):?><a class="btn btn-sm btn-outline-primary" href="contracts.php?edit=<?=$r['id']?>">ویرایش</a><?php endif;?> <?php if(has_permission('contracts','delete')):?><form method="post" class="d-inline" onsubmit="return confirm('این قرارداد حذف شود؟')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger">حذف</button></form><?php endif;?></td>
</tr>
<?php endforeach;?>
</tbody></table></div></div></div>
<script>
(function(){
 const type=document.getElementById('contractType'); if(!type)return;
 const end=document.getElementById('endDateWrap'), amount=document.getElementById('amountWrap'), deposit=document.getElementById('depositWrap'), label=document.getElementById('amountLabel');
 function sync(){
   const sale=type.value==='sale';
   end.style.display=sale?'none':'block';
   deposit.style.display=sale?'none':'block';
   label.textContent=sale?'مبلغ فروش':'اجاره ماهیانه';
 }
 type.addEventListener('change',sync); sync();
})();
</script>
<?php page_footer(); ?>