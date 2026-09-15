<?php
require_once __DIR__.'/config.php';
if (!empty($_SESSION['user'])) redirect('index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  check_csrf();
  $st=$pdo->prepare('SELECT * FROM users WHERE username=? AND is_active=1 LIMIT 1');
  $st->execute([trim(post('username'))]); $user=$st->fetch();
  if ($user && password_verify(post('password'), $user['password'])) { login_user($user); redirect('index.php'); }
  $error='نام کاربری یا رمز عبور اشتباه است.';
}
?><!doctype html><html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>ورود | برجینو</title><link rel="stylesheet" href="assets/style.css"></head><body><div class="login"><h1>🏢 برجینو</h1><p>ورود به سامانه مدیریت ساختمان</p><?php if($error):?><div class="error"><?=e($error)?></div><?php endif;?><form method="post"><?=csrf_field()?><label>نام کاربری<input name="username" required autofocus></label><label>رمز عبور<input type="password" name="password" required></label><button>ورود</button></form></div></body></html>
