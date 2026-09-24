<?php
require_once __DIR__.'/config.php'; require_page_permission('buildings');

if(isset($_POST['action']) && $_POST['action']==='delete'){
    check_csrf();
    $id=(int)post('id'); $st=$pdo->prepare('SELECT COUNT(*) FROM blocks WHERE building_id=?'); $st->execute([$id]);
    if((int)$st->fetchColumn()>0){flash('این ساختمان دارای بلوک است و ابتدا باید بلوک‌ها حذف شوند.');redirect('buildings.php');}
    $pdo->prepare('DELETE FROM buildings WHERE id=?')->execute([$id]); flash('ساختمان حذف شد.'); redirect('buildings.php');
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    check_csrf(); $id=(int)post('id');
    $data=[trim(post('name')),trim(post('postal_code'))?:null,post('building_type','residential'),trim(post('construction_date'))?:null,(int)post('parking_count',0),(int)post('storage_count',0),trim(post('province'))?:null,trim(post('city'))?:null,trim(post('address'))?:null,trim(post('manager_name'))?:null,trim(post('manager_phone'))?:null];
    if($id){$data[]=$id;$pdo->prepare('UPDATE buildings SET name=?,postal_code=?,building_type=?,construction_date=?,parking_count=?,storage_count=?,province=?,city=?,address=?,manager_name=?,manager_phone=? WHERE id=?')->execute($data);flash('ساختمان ویرایش شد.');}
    else{$pdo->prepare('INSERT INTO buildings(name,postal_code,building_type,construction_date,parking_count,storage_count,province,city,address,manager_name,manager_phone) VALUES(?,?,?,?,?,?,?,?,?,?,?)')->execute($data);flash('ساختمان اضافه شد.');}
    redirect('buildings.php');
}

$edit=null;if(isset($_GET['edit'])){$st=$pdo->prepare('SELECT * FROM buildings WHERE id=?');$st->execute([(int)$_GET['edit']]);$edit=$st->fetch();}

$search=trim((string)($_GET['search']??''));
$typeFilter=(string)($_GET['type']??'');
$where=[];$params=[];
if($search!==''){
    $where[]='(b.name LIKE ? OR b.city LIKE ? OR b.postal_code LIKE ? OR b.manager_name LIKE ?)';
    $term='%'.$search.'%'; $params=[$term,$term,$term,$term];
}
$types=['residential'=>'مسکونی','commercial'=>'تجاری','office'=>'اداری','educational'=>'آموزشی','other'=>'سایر'];
if(isset($types[$typeFilter])){$where[]='b.building_type=?';$params[]=$typeFilter;}
$sql='SELECT b.*,(SELECT COUNT(*) FROM blocks bl WHERE bl.building_id=b.id) block_count FROM buildings b';
if($where)$sql.=' WHERE '.implode(' AND ',$where);
$sql.=' ORDER BY b.id DESC';
$st=$pdo->prepare($sql);$st->execute($params);$rows=$st->fetchAll();

$stats=$pdo->query('SELECT COUNT(*) total, COALESCE(SUM(parking_count),0) parking, COALESCE(SUM(storage_count),0) storage FROM buildings')->fetch();
$buildingTypeLabels=$types;
$buildingTypeBadges=['residential'=>'badge-success','commercial'=>'badge-info','office'=>'badge-primary','educational'=>'badge-warning','other'=>'badge-secondary'];

page_header('ساختمان‌ها');
?>
<div class="content-header mb-4">
    <div>
        <h4 class="mb-1">مدیریت ساختمان‌ها</h4>
        <p class="text-muted mb-0">مدیریت اطلاعات پایه ساختمان‌ها، بلوک‌ها، پارکینگ و انبار.</p>
    </div>
    <a class="btn btn-primary" href="buildings.php?new=1"><i class="ti-plus ml-1"></i> ساختمان جدید</a>
</div>

<div class="row mb-4">
    <div class="col-md-4 mb-3 mb-md-0"><div class="card h-100"><div class="card-body d-flex align-items-center justify-content-between"><div><small class="text-muted d-block">تعداد ساختمان</small><h3 class="mb-0"><?=$stats['total']?></h3></div><i class="ti-home text-primary" style="font-size:30px"></i></div></div></div>
    <div class="col-md-4 mb-3 mb-md-0"><div class="card h-100"><div class="card-body d-flex align-items-center justify-content-between"><div><small class="text-muted d-block">کل پارکینگ</small><h3 class="mb-0"><?=$stats['parking']?></h3></div><i class="ti-car text-info" style="font-size:30px"></i></div></div></div>
    <div class="col-md-4"><div class="card h-100"><div class="card-body d-flex align-items-center justify-content-between"><div><small class="text-muted d-block">کل انبار</small><h3 class="mb-0"><?=$stats['storage']?></h3></div><i class="ti-package text-warning" style="font-size:30px"></i></div></div></div>
</div>

<?php if(isset($_GET['new'])||$edit):?>
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title mb-4"><?=$edit?'ویرایش ساختمان':'ایجاد ساختمان'?></h6>
        <form method="post"><?=csrf_field()?><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?>
            <div class="row">
                <div class="col-md-4 form-group"><label>نام ساختمان</label><input class="form-control" name="name" required value="<?=e($edit['name']??'')?>"></div>
                <div class="col-md-4 form-group"><label>کد پستی</label><input class="form-control" name="postal_code" value="<?=e($edit['postal_code']??'')?>"></div>
                <div class="col-md-4 form-group"><label>نوع ساختمان</label><select class="form-control" name="building_type"><?php foreach($types as $k=>$v):?><option value="<?=$k?>" <?=($edit['building_type']??'residential')===$k?'selected':''?>><?=$v?></option><?php endforeach;?></select></div>
                <div class="col-md-4 form-group"><label>تاریخ ساخت</label><input class="form-control" type="date" name="construction_date" value="<?=e($edit['construction_date']??'')?>"></div>
                <div class="col-md-4 form-group"><label>تعداد کل پارکینگ</label><input class="form-control" type="number" min="0" name="parking_count" value="<?=$edit['parking_count']??0?>"></div>
                <div class="col-md-4 form-group"><label>تعداد کل انبار</label><input class="form-control" type="number" min="0" name="storage_count" value="<?=$edit['storage_count']??0?>"></div>
                <div class="col-md-4 form-group"><label>استان</label><input class="form-control" name="province" value="<?=e($edit['province']??'')?>"></div>
                <div class="col-md-4 form-group"><label>شهر</label><input class="form-control" name="city" value="<?=e($edit['city']??'')?>"></div>
                <div class="col-md-4 form-group"><label>نام مدیر</label><input class="form-control" name="manager_name" value="<?=e($edit['manager_name']??'')?>"></div>
                <div class="col-md-4 form-group"><label>تلفن مدیر</label><input class="form-control" name="manager_phone" value="<?=e($edit['manager_phone']??'')?>"></div>
                <div class="col-md-8 form-group"><label>آدرس کامل</label><textarea class="form-control" name="address" rows="2"><?=e($edit['address']??'')?></textarea></div>
            </div>
            <button class="btn btn-primary">ذخیره</button> <a class="btn btn-outline-secondary" href="buildings.php">انصراف</a>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
        <h6 class="card-title mb-2 mb-md-0">فهرست ساختمان‌ها</h6>
        <form method="get" class="form-inline">
            <input class="form-control form-control-sm mr-2 mb-2 mb-md-0" name="search" value="<?=e($search)?>" placeholder="جستجوی نام، شهر، کد پستی...">
            <select class="form-control form-control-sm mr-2 mb-2 mb-md-0" name="type">
                <option value="">همه انواع</option>
                <?php foreach($types as $k=>$v):?><option value="<?=$k?>" <?=$typeFilter===$k?'selected':''?>><?=$v?></option><?php endforeach;?>
            </select>
            <button class="btn btn-sm btn-primary mb-2 mb-md-0"><i class="ti-search"></i> جستجو</button>
            <?php if($search!==''||$typeFilter!==''):?><a class="btn btn-sm btn-outline-secondary mr-2 mb-2 mb-md-0" href="buildings.php">پاک کردن</a><?php endif;?>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>نام ساختمان</th><th>نوع</th><th>شهر</th><th>بلوک‌ها</th><th>پارکینگ</th><th>انبار</th><th>مدیر</th><th>عملیات</th></tr></thead>
                <tbody>
                <?php if(!$rows):?><tr><td colspan="8" class="text-center text-muted py-4">ساختمانی با این فیلتر پیدا نشد.</td></tr><?php endif;?>
                <?php foreach($rows as $r):?>
                    <tr>
                        <td><strong><?=e($r['name'])?></strong><small class="d-block text-muted"><?=e($r['postal_code'])?></small></td>
                        <td><span class="badge <?=$buildingTypeBadges[$r['building_type']]??'badge-secondary'?>"><?=e($buildingTypeLabels[$r['building_type']]??$r['building_type'])?></span></td>
                        <td><?=e($r['city']?:'—')?></td>
                        <td><?=$r['block_count']?></td>
                        <td><?=$r['parking_count']?></td>
                        <td><?=$r['storage_count']?></td>
                        <td><?=e($r['manager_name']?:'—')?></td>
                        <td class="text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="buildings.php?edit=<?=$r['id']?>" title="ویرایش"><i class="ti-pencil"></i></a>
                            <form method="post" class="d-inline" onsubmit="return confirm('آیا از حذف این ساختمان اطمینان دارید؟')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger" title="حذف"><i class="ti-trash"></i></button></form>
                        </td>
                    </tr>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php page_footer(); ?>
