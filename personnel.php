<?php
require_once __DIR__.'/config.php';
require_page_permission('personnel');

$roles=['building_owner'=>'مالک ساختمان','manager'=>'مدیر ساختمان','security'=>'حراست','cleaning'=>'نظافت','repair'=>'تعمیرات'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 check_csrf(); $action=post('action');
 if($action==='save'){
  $id=(int)post('id'); require_permission('personnel',$id?'edit':'create'); $type=post('person_type','individual'); $role=post('role','manager');
  if(!isset($roles[$role])){flash('نقش پرسنل نامعتبر است.');redirect('personnel.php');}
  $first=trim(post('first_name'))?:null;$last=trim(post('last_name'))?:null;$company=trim(post('company_name'))?:null;
  if($type==='individual' && (!$first||!$last)){flash('نام و نام خانوادگی برای شخص حقیقی الزامی است.');redirect('personnel.php');}
  if($type==='company' && !$company){flash('نام شرکت برای شخص حقوقی الزامی است.');redirect('personnel.php');}
  $d=[$type,$role,$first,$last,$company,trim(post('national_code'))?:null,trim(post('phone'))?:null,trim(post('mobile'))?:null,trim(post('address'))?:null,trim(post('notes'))?:null];
  if($id){$d[]=$id;$pdo->prepare('UPDATE personnel SET person_type=?,role=?,first_name=?,last_name=?,company_name=?,national_code=?,phone=?,mobile=?,address=?,notes=? WHERE id=?')->execute($d);log_activity('update','personnel',$id,'ویرایش پرسنل');}
  else{$pdo->prepare('INSERT INTO personnel(person_type,role,first_name,last_name,company_name,national_code,phone,mobile,address,notes) VALUES(?,?,?,?,?,?,?,?,?,?)')->execute($d);$id=(int)$pdo->lastInsertId();log_activity('create','personnel',$id,'ایجاد پرسنل');}
  flash('پرسنل ذخیره شد.');redirect('personnel.php');
 }
 if($action==='delete'){require_permission('personnel','delete');$id=(int)post('id');$pdo->prepare('DELETE FROM personnel WHERE id=?')->execute([$id]);log_activity('delete','personnel',$id,'حذف پرسنل');flash('پرسنل حذف شد.');redirect('personnel.php');}
}
$edit=null;if(isset($_GET['edit'])){$st=$pdo->prepare('SELECT * FROM personnel WHERE id=?');$st->execute([(int)$_GET['edit']]);$edit=$st->fetch();}
$rows=$pdo->query('SELECT * FROM personnel ORDER BY id DESC')->fetchAll();page_header('پرسنل');
?>
<div class="content-header mb-4"><div><h4 class="mb-1">مدیریت پرسنل</h4><p class="text-muted mb-0">پرسنل حقیقی و حقوقی ساختمان بر اساس نقش.</p></div><?php if(has_permission('personnel','create')):?><a class="btn btn-primary" href="personnel.php?new=1">پرسنل جدید</a><?php endif;?></div>
<?php if(isset($_GET['new'])||$edit): ?><div class="card mb-4"><div class="card-body"><h6 class="card-title mb-4"><?=$edit?'ویرایش پرسنل':'ایجاد پرسنل'?></h6><form method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?><div class="row">
<div class="col-md-2 form-group"><label>نوع</label><select class="form-control" name="person_type"><option value="individual" <?=($edit['person_type']??'individual')==='individual'?'selected':''?>>حقیقی</option><option value="company" <?=($edit['person_type']??'')==='company'?'selected':''?>>حقوقی</option></select></div>
<div class="col-md-3 form-group"><label>نقش</label><select class="form-control" name="role"><?php foreach($roles as $v=>$l):?><option value="<?=$v?>" <?=($edit['role']??'manager')===$v?'selected':''?>><?=$l?></option><?php endforeach;?></select></div>
<div class="col-md-3 form-group"><label>نام</label><input class="form-control" name="first_name" value="<?=e($edit['first_name']??'')?>"></div>
<div class="col-md-3 form-group"><label>نام خانوادگی</label><input class="form-control" name="last_name" value="<?=e($edit['last_name']??'')?>"></div>
<div class="col-md-4 form-group"><label>نام شرکت</label><input class="form-control" name="company_name" value="<?=e($edit['company_name']??'')?>"></div>
<div class="col-md-2 form-group"><label>کد ملی/شناسه</label><input class="form-control" name="national_code" value="<?=e($edit['national_code']??'')?>"></div>
<div class="col-md-2 form-group"><label>تلفن</label><input class="form-control" name="phone" value="<?=e($edit['phone']??'')?>"></div>
<div class="col-md-2 form-group"><label>موبایل</label><input class="form-control" name="mobile" value="<?=e($edit['mobile']??'')?>"></div>
<div class="col-md-12 form-group"><label>آدرس</label><textarea class="form-control" name="address"><?=e($edit['address']??'')?></textarea></div>
<div class="col-md-12 form-group"><label>توضیحات</label><textarea class="form-control" name="notes"><?=e($edit['notes']??'')?></textarea></div>
</div><button class="btn btn-primary">ذخیره</button> <a class="btn btn-outline-secondary" href="personnel.php">انصراف</a></form></div></div><?php endif;?>
<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>نوع</th><th>نقش</th><th>نام</th><th>تماس</th><th>شناسه</th><th>عملیات</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=$r['person_type']==='company'?'حقوقی':'حقیقی'?></td><td><?=e($roles[$r['role']]??$r['role'])?></td><td><?=e($r['person_type']==='company'?$r['company_name']:trim($r['first_name'].' '.$r['last_name']))?></td><td><?=e($r['mobile']?:$r['phone'])?></td><td><?=e($r['national_code'])?></td><td><?php if(has_permission('personnel','edit')):?><a class="btn btn-sm btn-outline-primary" href="personnel.php?edit=<?=$r['id']?>">ویرایش</a><?php endif;?> <?php if(has_permission('personnel','delete')):?><form method="post" class="d-inline" onsubmit="return confirm('حذف شود؟')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger">حذف</button></form><?php endif;?></td></tr><?php endforeach;?></tbody></table></div></div></div>
<?php page_footer(); ?>