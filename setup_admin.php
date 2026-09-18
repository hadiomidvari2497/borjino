<?php
require_once __DIR__.'/config.php';
$message='';
if($_SERVER['REQUEST_METHOD']==='POST'){check_csrf();$u=trim(post('username'));$p=post('password');$n=trim(post('full_name'));if($u && $p){
$pdo->exec("INSERT INTO access_groups(name,description,is_system) SELECT 'administrators','دسترسی کامل سامانه',1 WHERE NOT EXISTS (SELECT 1 FROM access_groups WHERE name='administrators')");
$groupId=(int)$pdo->query("SELECT id FROM access_groups WHERE name='administrators' LIMIT 1")->fetchColumn();
$st=$pdo->prepare('SELECT id FROM users WHERE username=? LIMIT 1');$st->execute([$u]);$existing=$st->fetchColumn();
if($existing){$pdo->prepare('UPDATE users SET password=?,full_name=?,access_group_id=?,is_active=1 WHERE id=?')->execute([password_hash($p,PASSWORD_DEFAULT),$n,$groupId,$existing]);$message='کاربر مدیر به‌روزرسانی شد. حالا از login.php وارد شو.';}
else{$pdo->prepare('INSERT INTO users(username,password,full_name,access_group_id,is_active) VALUES(?,?,?,?,1)')->execute([$u,password_hash($p,PASSWORD_DEFAULT),$n,$groupId]);$message='کاربر مدیر ساخته شد. حالا از login.php وارد شو.';}
}}
?><!doctype html><html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ساخت مدیر</title><link rel="stylesheet" href="assets/style.css"></head><body><div class="login"><h1>ساخت کاربر مدیر</h1><?php if($message):?><div class="flash"><?=e($message)?></div><?php endif;?><form method="post"><?=csrf_field()?><label>نام کاربری<input name="username" value="admin" required></label><label>نام نمایشی<input name="full_name" value="مدیر سیستم"></label><label>رمز عبور<input type="password" name="password" required minlength="6"></label><button>ساخت کاربر</button></form><p class="muted">بعد از ساخت، فایل setup_admin.php را حذف کن.</p></div></body></html>
