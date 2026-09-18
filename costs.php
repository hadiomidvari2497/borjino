<?php
require_once __DIR__.'/config.php';
require_page_permission('costs');

$types=['fixed'=>'هزینه ثابت','variable'=>'هزینه متغیر'];
$categories=['general'=>'عمومی','utility'=>'قبوض مشاع','repair'=>'تعمیرات','other'=>'سایر'];
$allocations=['equal'=>'مساوی','area'=>'بر اساس متراژ','person'=>'بر اساس تعداد نفر','combination'=>'ترکیبی'];

if (isset($_GET['delete'])) {
    check_csrf();
    $id=(int)$_GET['delete'];
    $pdo->prepare('DELETE FROM costs WHERE id=?')->execute([$id]);
    log_activity('delete','costs',$id,'حذف هزینه');
    flash('هزینه حذف شد.');
    redirect('costs.php');
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $id=(int)post('id');
    $buildingId=(int)post('building_id');
    $type=post('cost_type','fixed');
    $category=post('category','general');
    $title=trim(post('title'));
    $amount=(float)post('amount',0);
    $period=trim(post('period'))?:null;
    $isCommon=(int)post('is_common',0) ? 1 : 0;
    $allocation=$type==='variable' ? (post('allocation_method')?:null) : null;
    $areaPercent=$allocation==='combination' ? (float)post('allocation_area_percent',0) : null;
    $personPercent=$allocation==='combination' ? (float)post('allocation_person_percent',0) : null;
    $notes=trim(post('notes'))?:null;

    $errors=[];
    if(!$buildingId) $errors[]='ساختمان را انتخاب کنید.';
    if(!isset($types[$type])) $errors[]='نوع هزینه نامعتبر است.';
    if(!isset($categories[$category])) $errors[]='دسته‌بندی هزینه نامعتبر است.';
    if($title==='') $errors[]='عنوان هزینه الزامی است.';
    if($amount<0) $errors[]='مبلغ هزینه نمی‌تواند منفی باشد.';
    if($type==='variable' && !$allocation) $errors[]='روش تقسیم هزینه متغیر را انتخاب کنید.';
    if($allocation==='combination' && ($areaPercent<0 || $personPercent<0 || ($areaPercent+$personPercent)!==100.0)) $errors[]='در روش ترکیبی مجموع سهم متراژ و نفر باید 100 درصد باشد.';

    if($errors) {
        flash(implode(' ', $errors));
        redirect('costs.php?new=1');
    }

    if($id) {
        $st=$pdo->prepare('UPDATE costs SET building_id=?,cost_type=?,category=?,title=?,amount=?,period=?,allocation_method=?,allocation_area_percent=?,allocation_person_percent=?,is_common=?,notes=? WHERE id=?');
        $st->execute([$buildingId,$type,$category,$title,$amount,$period,$allocation,$areaPercent,$personPercent,$isCommon,$notes,$id]);
        log_activity('update','costs',$id,'ویرایش هزینه');
        flash('هزینه ویرایش شد.');
    } else {
        $st=$pdo->prepare('INSERT INTO costs(building_id,cost_type,category,title,amount,period,allocation_method,allocation_area_percent,allocation_person_percent,is_common,notes) VALUES(?,?,?,?,?,?,?,?,?,?,?)');
        $st->execute([$buildingId,$type,$category,$title,$amount,$period,$allocation,$areaPercent,$personPercent,$isCommon,$notes]);
        $newId=(int)$pdo->lastInsertId();
        log_activity('create','costs',$newId,'ثبت هزینه');
        flash('هزینه ثبت شد.');
    }
    redirect('costs.php');
}

$edit=null;
if(isset($_GET['edit'])) {
    $st=$pdo->prepare('SELECT * FROM costs WHERE id=?');
    $st->execute([(int)$_GET['edit']]);
    $edit=$st->fetch();
}
$buildings=$pdo->query('SELECT id,name FROM buildings ORDER BY name')->fetchAll();
$rows=$pdo->query('SELECT c.*,b.name building_name FROM costs c JOIN buildings b ON b.id=c.building_id ORDER BY c.id DESC')->fetchAll();

page_header('هزینه‌ها');
?>
<div class="content-header mb-4">
  <div><h4 class="mb-1">مدیریت هزینه‌ها</h4><p class="text-muted mb-0">ثبت هزینه‌های ثابت، متغیر، قبوض مشاع و تعمیرات برای هر ساختمان.</p></div>
  <a class="btn btn-primary" href="costs.php?new=1"><i class="ti-plus ml-1"></i> هزینه جدید</a>
</div>

<?php if(isset($_GET['new'])||$edit): ?>
<div class="card mb-4"><div class="card-body">
<h6 class="card-title mb-4"><?= $edit?'ویرایش هزینه':'ثبت هزینه' ?></h6>
<form method="post"><?=csrf_field()?><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?>
<div class="row">
<div class="col-md-4 form-group"><label>ساختمان</label><select class="form-control" name="building_id" required><option value="">انتخاب کنید</option><?php foreach($buildings as $b):?><option value="<?=$b['id']?>" <?=((int)($edit['building_id']??0)===$b['id'])?'selected':''?>><?=e($b['name'])?></option><?php endforeach;?></select></div>
<div class="col-md-4 form-group"><label>نوع هزینه</label><select class="form-control" name="cost_type" id="cost_type"><?php foreach($types as $k=>$v):?><option value="<?=$k?>" <?=($edit['cost_type']??'fixed')===$k?'selected':''?>><?=$v?></option><?php endforeach;?></select></div>
<div class="col-md-4 form-group"><label>دسته‌بندی</label><select class="form-control" name="category"><?php foreach($categories as $k=>$v):?><option value="<?=$k?>" <?=($edit['category']??'general')===$k?'selected':''?>><?=$v?></option><?php endforeach;?></select></div>
<div class="col-md-4 form-group"><label>عنوان</label><input class="form-control" name="title" required value="<?=e($edit['title']??'')?>"></div>
<div class="col-md-4 form-group"><label>مبلغ</label><input class="form-control" type="number" min="0" step="0.01" name="amount" required value="<?=e($edit['amount']??0)?>"></div>
<div class="col-md-4 form-group"><label>دوره</label><input class="form-control" name="period" placeholder="1405-06" value="<?=e($edit['period']??'')?>"></div>
<div class="col-md-4 form-group" id="allocationBox"><label>روش تقسیم هزینه متغیر</label><select class="form-control" name="allocation_method" id="allocation_method"><option value="">انتخاب کنید</option><?php foreach($allocations as $k=>$v):?><option value="<?=$k?>" <?=($edit['allocation_method']??'')===$k?'selected':''?>><?=$v?></option><?php endforeach;?></select></div>
<div class="col-md-4 form-group" id="areaPercentBox"><label>سهم متراژ (%)</label><input class="form-control" type="number" min="0" max="100" step="0.01" name="allocation_area_percent" value="<?=e($edit['allocation_area_percent']??80)?>"></div>
<div class="col-md-4 form-group" id="personPercentBox"><label>سهم نفر (%)</label><input class="form-control" type="number" min="0" max="100" step="0.01" name="allocation_person_percent" value="<?=e($edit['allocation_person_percent']??20)?>"></div>
<div class="col-md-4 form-group"><div class="custom-control custom-checkbox mt-4"><input type="checkbox" class="custom-control-input" id="is_common" name="is_common" value="1" <?=!empty($edit['is_common'])?'checked':''?>><label class="custom-control-label" for="is_common">هزینه مشاع است</label></div></div>
<div class="col-12 form-group"><label>توضیحات</label><textarea class="form-control" name="notes" rows="3"><?=e($edit['notes']??'')?></textarea></div>
</div>
<button class="btn btn-primary">ذخیره</button> <a class="btn btn-outline-secondary" href="costs.php">انصراف</a>
</form></div></div>
<?php endif; ?>

<div class="card"><div class="card-body"><div class="table-responsive">
<table class="table table-hover mb-0"><thead><tr><th>ساختمان</th><th>عنوان</th><th>نوع</th><th>دسته</th><th>دوره</th><th>مبلغ</th><th>تقسیم</th><th>مشاع</th><th>عملیات</th></tr></thead><tbody>
<?php foreach($rows as $r):?><tr>
<td><?=e($r['building_name'])?></td><td><?=e($r['title'])?></td><td><?=e($types[$r['cost_type']]??$r['cost_type'])?></td><td><?=e($categories[$r['category']]??$r['category'])?></td><td><?=e($r['period']??'')?></td><td><?=money($r['amount'])?></td><td><?=e($r['allocation_method'] ? ($allocations[$r['allocation_method']]??$r['allocation_method']) : '—')?></td><td><?=$r['is_common']?'بله':'خیر'?></td>
<td><a class="btn btn-sm btn-outline-primary" href="costs.php?edit=<?=$r['id']?>">ویرایش</a> <a class="btn btn-sm btn-outline-danger" href="costs.php?delete=<?=$r['id']?>&csrf=<?=e(csrf_token())?>" onclick="return confirm('این هزینه حذف شود؟')">حذف</a></td>
</tr><?php endforeach;?>
</tbody></table></div></div></div>
<script>
(function(){
  const type=document.getElementById('cost_type'), box=document.getElementById('allocationBox'), a=document.getElementById('allocation_method'), ap=document.getElementById('areaPercentBox'), pp=document.getElementById('personPercentBox');
  function sync(){
    const variable=type && type.value==='variable';
    if(box) box.style.display=variable?'block':'none';
    const combo=variable && a && a.value==='combination';
    if(ap) ap.style.display=combo?'block':'none';
    if(pp) pp.style.display=combo?'block':'none';
  }
  if(type) type.addEventListener('change',sync); if(a) a.addEventListener('change',sync); sync();
})();
</script>
<?php page_footer(); ?>
