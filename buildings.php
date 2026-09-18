<?php
require_once __DIR__.'/config.php'; require_page_permission('buildings');

if(isset($_GET['delete'])){
    $id=(int)$_GET['delete']; $st=$pdo->prepare('SELECT COUNT(*) FROM blocks WHERE building_id=?'); $st->execute([$id]);
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
$rows=$pdo->query('SELECT b.*,(SELECT COUNT(*) FROM blocks bl WHERE bl.building_id=b.id) block_count FROM buildings b ORDER BY b.id DESC')->fetchAll();
page_header('ساختمان‌ها');
?>
<div class="content-header mb-4"><div><h4 class="mb-1">مدیریت ساختمان‌ها</h4><p class="text-muted mb-0">اطلاعات کلی ساختمان؛ طبقات و واحدهای هر طبقه در بخش بلوک تعریف می‌شوند.</p></div><a class="btn btn-primary" href="buildings.php?new=1"><i class="ti-plus ml-1"></i> ساختمان جدید</a></div>
<?php if(isset($_GET['new'])||$edit):?><div class="card mb-4"><div class="card-body"><h6 class="card-title mb-4"><?=$edit?'ویرایش ساختمان':'ایجاد ساختمان'?></h6><form method="post"><?=csrf_field()?><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?><div class="row">
<div class="col-md-4 form-group"><label>نام ساختمان</label><input class="form-control" name="name" required value="<?=e($edit['name']??'')?>"></div>
<div class="col-md-4 form-group"><label>کد پستی</label><input class="form-control" name="postal_code" value="<?=e($edit['postal_code']??'')?>"></div>
<div class="col-md-4 form-group"><label>نوع ساختمان</label><select class="form-control" name="building_type"><?php foreach(['residential'=>'مسکونی','commercial'=>'تجاری','office'=>'اداری','educational'=>'آموزشی','other'=>'سایر'] as $k=>$v):?><option value="<?=$k?>" <?=($edit['building_type']??'residential')===$k?'selected':''?>><?=$v?></option><?php endforeach;?></select></div>
<div class="col-md-4 form-group"><label>تاریخ ساخت</label><input class="form-control" type="date" name="construction_date" value="<?=e($edit['construction_date']??'')?>"></div>
<div class="col-md-4 form-group"><label>تعداد کل پارکینگ</label><input class="form-control" type="number" min="0" name="parking_count" value="<?=$edit['parking_count']??0?>"></div>
<div class="col-md-4 form-group"><label>تعداد کل انبار</label><input class="form-control" type="number" min="0" name="storage_count" value="<?=$edit['storage_count']??0?>"></div>
<div class="col-md-4 form-group"><label>استان</label><input class="form-control" name="province" value="<?=e($edit['province']??'')?>"></div>
<div class="col-md-4 form-group"><label>شهر</label><input class="form-control" name="city" value="<?=e($edit['city']??'')?>"></div>
<div class="col-md-4 form-group"><label>نام مدیر</label><input class="form-control" name="manager_name" value="<?=e($edit['manager_name']??'')?>"></div>
<div class="col-md-4 form-group"><label>تلفن مدیر</label><input class="form-control" name="manager_phone" value="<?=e($edit['manager_phone']??'')?>"></div>
<div class="col-md-8 form-group"><label>آدرس کامل</label><textarea class="form-control" name="address" rows="2"><?=e($edit['address']??'')?></textarea></div>
</div><button class="btn btn-primary">ذخیره</button> <a class="btn btn-outline-secondary" href="buildings.php">انصراف</a></form></div></div><?php endif; ?>
<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>نام ساختمان</th><th>نوع</th><th>شهر</th><th>بلوک‌ها</th><th>پارکینگ</th><th>انبار</th><th>مدیر</th><th>عملیات</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['name'])?></td><td><?=e(['residential'=>'مسکونی','commercial'=>'تجاری','office'=>'اداری','educational'=>'آموزشی','other'=>'سایر'][$r['building_type']]??$r['building_type'])?></td><td><?=e($r['city'])?></td><td><?=$r['block_count']?></td><td><?=$r['parking_count']?></td><td><?=$r['storage_count']?></td><td><?=e($r['manager_name'])?></td><td><a class="btn btn-sm btn-outline-primary" href="buildings.php?edit=<?=$r['id']?>">ویرایش</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')" href="buildings.php?delete=<?=$r['id']?>">حذف</a></td></tr><?php endforeach;?></tbody></table></div></div></div>
<?php page_footer(); ?>
