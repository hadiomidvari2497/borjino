<?php
require_once __DIR__.'/config.php';
if (!empty($_SESSION['user'])) redirect('index.php');
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $st=$pdo->prepare('SELECT * FROM users WHERE username=? AND is_active=1 LIMIT 1');
    $st->execute([trim(post('username'))]);
    $user=$st->fetch();
    if ($user && password_verify(post('password'), $user['password'])) {
        login_user($user);
        redirect('index.php');
    }
    $error='نام کاربری یا رمز عبور اشتباه است.';
}
$tpl='https://raw.githubusercontent.com/parhamIH/Vira-online-shop/main/frontend/templateAdmin/';
?><!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ورود - برجینو</title>
    <link rel="shortcut icon" href="<?= $tpl ?>assets/media/image/favicon.png">
    <meta name="theme-color" content="#5867dd">
    <link rel="stylesheet" href="<?= $tpl ?>vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="<?= $tpl ?>assets/css/app.css" type="text/css">
</head>
<body class="form-membership">
<div class="page-loader"><div class="spinner-border"></div></div>
<div class="form-wrapper">
    <div class="logo"><img src="<?= $tpl ?>assets/media/image/logo-sm.png" alt="برجینو"></div>
    <h5>ورود به برجینو</h5>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="post" action="login.php">
        <?= csrf_field() ?>
        <div class="form-group"><input type="text" class="form-control text-left" name="username" placeholder="نام کاربری" dir="ltr" required autofocus></div>
        <div class="form-group"><input type="password" class="form-control text-left" name="password" placeholder="رمز عبور" dir="ltr" required></div>
        <div class="form-group d-flex justify-content-between text-left mb-4">
            <div class="custom-control custom-checkbox"><input type="checkbox" class="custom-control-input" checked id="remember"><label class="custom-control-label" for="remember">به خاطر سپاری</label></div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">ورود</button>
    </form>
</div>
<script src="<?= $tpl ?>vendors/bundle.js"></script>
<script src="<?= $tpl ?>assets/js/app.js"></script>
</body>
</html>
