<?php
require_once __DIR__.'/config.php';
require_permission('users','view');

if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $action=post('action');
    if ($action==='save') {
        require_permission('users',post('id')?'edit':'create');
        $id=(int)post('id');
        $username=trim(post('username'));
        $fullName=trim(post('full_name'));
        $phone=trim(post('phone'))?:null;
        $groupId=(int)post('access_group_id');
        $active=post('is_active')==='1'?1:0;
        $password=post('password');
        if (!$username || !$fullName || !$groupId || (!$id && !$password)) { flash('اطلاعات اجباری کاربر کامل نیست.'); redirect('users.php'); }
        if ($id) {
            $st=$pdo->prepare('SELECT username FROM users WHERE id=?'); $st->execute([$id]); $target=$st->fetch();
            if (!$target) { flash('کاربر پیدا نشد.'); redirect('users.php'); }
            if ($target['username']==='admin') {
                $groupId=(int)$pdo->query("SELECT id FROM access_groups WHERE name='administrators' LIMIT 1")->fetchColumn();
                $active=1;
                if ($username!=='admin') $username='admin';
            }
            $sql='UPDATE users SET username=?,full_name=?,phone=?,access_group_id=?,is_active=?'.($password?',password=?':'').' WHERE id=?';
            $params=[$username,$fullName,$phone,$groupId,$active];
            if ($password) $params[]=password_hash($password,PASSWORD_DEFAULT);
            $params[]=$id; $pdo->prepare($sql)->execute($params); log_activity('update','users',$id,'ویرایش کاربر'); flash('کاربر ویرایش شد.');
        } else {
            $pdo->prepare('INSERT INTO users(username,password,full_name,phone,access_group_id,is_active) VALUES(?,?,?,?,?,?)')
                ->execute([$username,password_hash($password,PASSWORD_DEFAULT),$fullName,$phone,$groupId,$active]);
            $newId=(int)$pdo->lastInsertId(); log_activity('create','users',$newId,'ایجاد کاربر'); flash('کاربر ایجاد شد.');
        }
        redirect('users.php');
    }
    if ($action==='delete') {
        require_permission('users','delete');
        $id=(int)post('id');
        $st=$pdo->prepare('SELECT username FROM users WHERE id=?'); $st->execute([$id]); $target=$st->fetch();
        if (!$target || $target['username']==='admin') { flash('کاربر admin قابل حذف نیست.'); redirect('users.php'); }
        $pdo->prepare('DELETE FROM users WHERE id=?')->execute([$id]); log_activity('delete','users',$id,'حذف کاربر'); flash('کاربر حذف شد.'); redirect('users.php');
    }
}
$groups=$pdo->query('SELECT id,name FROM access_groups ORDER BY is_system DESC,name')->fetchAll();
$edit=null;if(isset($_GET['edit'])){$st=$pdo->prepare('SELECT * FROM users WHERE id=?');$st->execute([(int)$_GET['edit']]);$edit=$st->fetch();}
$rows=$pdo->query("SELECT u.*,g.name group_name FROM users u LEFT JOIN access_groups g ON g.id=u.access_group_id ORDER BY u.id DESC")->fetchAll();
page_header('کاربران');
?>
<div class="content-header mb-4"><div><h4 class="mb-1">مدیریت کاربران</h4><p class="text-muted mb-0">کاربران سامانه و گروه دسترسی آن‌ها.</p></div><a class="btn btn-primary" href="users.php?new=1">کاربر جدید</a></div>
<?php if(isset($_GET['new'])||$edit): ?><div class="card mb-4"><div class="card-body"><h6 class="card-title mb-4"><?=$edit?'ویرایش کاربر':'ایجاد کاربر'?></h6><form method="post"><?=csrf_field()?><input type="hidden" name="action" value="save"><?php if($edit):?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif;?><div class="row">
<div class="col-md-3 form-group"><label>نام کاربری</label><input class="form-control" name="username" required value="<?=e($edit['username']??'')?>"></div>
<div class="col-md-3 form-group"><label>نام و نام خانوادگی</label><input class="form-control" name="full_name" required value="<?=e($edit['full_name']??'')?>"></div>
<div class="col-md-2 form-group"><label>تلفن</label><input class="form-control" name="phone" value="<?=e($edit['phone']??'')?>"></div>
<div class="col-md-2 form-group"><label>گروه دسترسی</label><select class="form-control" name="access_group_id" required><?php foreach($groups as $g):?><option value="<?=$g['id']?>" <?=($edit['access_group_id']??'')==$g['id']?'selected':''?>><?=e($g['name'])?></option><?php endforeach;?></select></div>
<div class="col-md-2 form-group"><label>وضعیت</label><select class="form-control" name="is_active"><option value="1" <?=($edit['is_active']??1)?'selected':''?>>فعال</option><option value="0" <?=isset($edit)&&!$edit['is_active']?'selected':''?>>غیرفعال</option></select></div>
<div class="col-md-4 form-group"><label>رمز عبور <?= $edit?'(در صورت تغییر وارد شود)':''?></label><input class="form-control" type="password" name="password" <?=$edit?'':'required'?> minlength="6"></div>
</div><button class="btn btn-primary">ذخیره</button> <a class="btn btn-outline-secondary" href="users.php">انصراف</a></form></div></div><?php endif;?>
<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-hover"><thead><tr><th>نام کاربری</th><th>نام</th><th>گروه</th><th>تلفن</th><th>وضعیت</th><th>آخرین ورود</th><th>عملیات</th></tr></thead><tbody><?php foreach($rows as $r):?><tr><td><?=e($r['username'])?></td><td><?=e($r['full_name'])?></td><td><?=e($r['group_name']??'-')?></td><td><?=e($r['phone'])?></td><td><?=$r['is_active']?'فعال':'غیرفعال'?></td><td><?=e($r['last_login_at']??'-')?></td><td><a class="btn btn-sm btn-outline-primary" href="users.php?edit=<?=$r['id']?>">ویرایش</a><?php if($r['username']!=='admin'):?><form method="post" class="d-inline" onsubmit="return confirm('حذف شود؟')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger">حذف</button></form><?php endif;?></td></tr><?php endforeach;?></tbody></table></div></div></div>
<?php page_footer(); ?>