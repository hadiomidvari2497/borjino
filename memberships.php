<?php
require_once __DIR__.'/config.php';
require_page_permission('memberships');

if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $action=post('action');
    if ($action==='save') {
        $id=(int)post('id');
        require_permission('memberships',$id?'edit':'create');
        $unit=(int)post('unit_id'); $person=(int)post('person_id'); $role=post('role','resident');
        if(!$unit||!$person){flash('واحد و شخص الزامی است.');redirect('memberships.php');}
        $params=[$unit,$person,$role,post('is_primary')==='1'?1:0,post('start_date')?:null,post('end_date')?:null];
        if($id){$params[]=$id;$pdo->prepare('UPDATE memberships SET unit_id=?,person_id=?,role=?,is_primary=?,start_date=?,end_date=? WHERE id=?')->execute($params);log_activity('update','memberships',$id,'ویرایش عضو');}
        else{$pdo->prepare('INSERT INTO memberships(unit_id,person_id,role,is_primary,start_date,end_date) VALUES(?,?,?,?,?,?)')->execute($params);$id=(int)$pdo->lastInsertId();log_activity('create','memberships',$id,'ایجاد عضو');}
        flash('عضو ذخیره شد.'); redirect('memberships.php');
    }
    if($action==='delete'){
        require_permission('memberships','delete'); $id=(int)post('id');
        $pdo->prepare('DELETE FROM memberships WHERE id=?')->execute([$id]); log_activity('delete','memberships',$id,'حذف عضو'); flash('عضو حذف شد.'); redirect('memberships.php');
    }
}
$units=$pdo->query('SELECT id,unit_no FROM units ORDER BY unit_no')->fetchAll();
$persons=$pdo->query('SELECT id,first_name,last_name FROM persons ORDER BY last_name,first_name')->fetchAll();
$edit=null;
if(isset($_GET['edit'])){$st=$pdo->prepare('SELECT * FROM memberships WHERE id=?');$st->execute([(int)$_GET['edit']]);$edit=$st->fetch();}
$rows=$pdo->query("SELECT m.*,u.unit_no,CONCAT(p.first_name,' ',p.last_name) person_name FROM memberships m JOIN units u ON u.id=m.unit_id JOIN persons p ON p.id=m.person_id ORDER BY m.id DESC")->fetchAll();
page_header('اعضای ساختمان');
?>
<div class="content-header mb-4"><div><h4 class="mb-1">اعضای ساختمان</h4><p class="text-muted mb-0">مالکین، مستأجرین و سایر ساکنین مرتبط با واحدها.</p></div><?php if(has_permission('memberships','create')):?><a class="btn btn-primary" href="memberships.php?new=1">عضو جدید</a><?php endif;?></div>
<?php if(isset($_GET['new'])||$edit): ?><div class="card mb-4"><div class="card-body"><h6 class="card-title mb-4"><?= $edit?'ویرایش عضو':'ایجاد عضو' ?></h6><form method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?><div class="row">
<div class="col-md-3 form-group"><label>واحد</label><select class="form-control" name="unit_id" required><?php foreach($units as $u):?><option value="<?=$u['id']?>" <?=($edit['unit_id']??'')==$u['id']?'selected':''?>>واحد <?=e($u['unit_no'])?></option><?php endforeach;?></select></div>
<div class="col-md-3 form-group"><label>شخص</label><select class="form-control" name="person_id" required><?php foreach($persons as $p):?><option value="<?=$p['id']?>" <?=($edit['person_id']??'')==$p['id']?'selected':''?>><?=e($p['first_name'].' '.$p['last_name'])?></option><?php endforeach;?></select></div>
<div class="col-md-2 form-group"><label>نقش</label><select class="form-control" name="role"><?php foreach(['owner'=>'مالک','tenant'=>'مستأجر','resident'=>'ساکن','family'=>'عضو خانواده','other'=>'سایر'] as $v=>$l):?><option value="<?=$v?>" <?=($edit['role']??'resident')===$v?'selected':''?>><?=$l?></option><?php endforeach;?></select></div>
<div class="col-md-2 form-group"><label>عضو اصلی</label><select class="form-control" name="is_primary"><option value="1" <?=($edit['is_primary']??0)?'selected':''?>>بله</option><option value="0" <?=isset($edit)&&!$edit['is_primary']?'selected':''?>>خیر</option></select></div>
<div class="col-md-2 form-group"><label>تاریخ شروع</label><input class="form-control" type="date" name="start_date" value="<?=e($edit['start_date']??'')?>"></div>
<div class="col-md-2 form-group"><label>تاریخ پایان</label><input class="form-control" type="date" name="end_date" value="<?=e($edit['end_date']??'')?>"></div>
</div><button class="btn btn-primary">ذخیره</button> <a class="btn btn-outline-secondary" href="memberships.php">انصراف</a></form></div></div><?php endif;?>
<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>واحد</th><th>عضو</th><th>نقش</th><th>اصلی</th><th>شروع</th><th>پایان</th><th>عملیات</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['unit_no'])?></td><td><?=e($r['person_name'])?></td><td><?=e($r['role'])?></td><td><?=$r['is_primary']?'بله':'خیر'?></td><td><?=e($r['start_date']??'-')?></td><td><?=e($r['end_date']??'-')?></td><td><?php if(has_permission('memberships','edit')):?><a class="btn btn-sm btn-outline-primary" href="memberships.php?edit=<?=$r['id']?>">ویرایش</a><?php endif;?> <?php if(has_permission('memberships','delete')):?><form method="post" class="d-inline" onsubmit="return confirm('حذف شود؟')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger">حذف</button></form><?php endif;?></td></tr><?php endforeach;?></tbody></table></div></div></div>
<?php page_footer(); ?>