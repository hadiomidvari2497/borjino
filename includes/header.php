<?php
function page_header(string $title='برجینو'): void {
    $u = current_user();
    $f = flash();
    $page_title = $title;
    $tpl = 'https://raw.githubusercontent.com/parhamIH/Vira-online-shop/main/frontend/templateAdmin/';
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= e($page_title) ?> - برجینو</title>
    <link rel="shortcut icon" href="<?= $tpl ?>assets/media/image/favicon.png">
    <meta name="theme-color" content="#5867dd">
    <link rel="stylesheet" href="<?= $tpl ?>vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="<?= $tpl ?>assets/css/app.css" type="text/css">
</head>
<body>
<div class="page-loader"><div class="spinner-border"></div></div>
<div class="sidebar" id="userProfile">
    <div class="text-center p-4">
        <figure class="avatar avatar-state-success avatar-lg mb-4"><img src="<?= $tpl ?>assets/media/image/avatar.jpg" class="rounded-circle" alt="avatar"></figure>
        <h4 class="text-primary m-b-10"><?= e($u['full_name'] ?? 'مدیر سیستم') ?></h4>
        <p class="text-muted d-flex align-items-center justify-content-center line-height-0 mb-0">مدیر سامانه</p>
    </div>
    <hr class="m-0"><div class="p-4"><div class="mb-4"><h6 class="font-size-13 mb-3">سامانه مدیریت ساختمان</h6><p class="text-muted">برجینو</p></div></div>
</div>
<div class="sidebar" id="settings"><header><i class="ti-settings"></i> تنظیمات</header><div class="p-4"><p class="text-muted">تنظیمات سامانه در این بخش قرار می‌گیرد.</p></div></div>
<div class="navigation">
    <div class="navigation-icon-menu">
        <ul>
            <li class="active" data-toggle="tooltip" title="داشبورد"><a href="#navigationDashboards"><i class="icon ti-pie-chart"></i></a></li>
            <li data-toggle="tooltip" title="ساختمان"><a href="#navigationBuilding"><i class="icon ti-home"></i></a></li>
            <li data-toggle="tooltip" title="مالی"><a href="#navigationFinance"><i class="icon ti-wallet"></i></a></li>
            <li data-toggle="tooltip" title="افراد"><a href="#navigationPeople"><i class="icon ti-user"></i></a></li>
        </ul>
        <ul><li data-toggle="tooltip" title="تنظیمات"><a href="#settings"><i class="icon ti-settings"></i></a></li><li data-toggle="tooltip" title="خروج"><a href="logout.php" class="go-to-page"><i class="icon ti-power-off"></i></a></li></ul>
    </div>
    <div class="navigation-menu-body">
        <ul id="navigationDashboards" class="navigation-active"><li class="navigation-divider">داشبورد</li><li><a class="active" href="index.php">داشبورد اصلی</a></li></ul>
        <ul id="navigationBuilding"><li class="navigation-divider">مدیریت ساختمان</li><li><a href="buildings.php">ساختمان‌ها</a></li><li><a href="blocks.php">بلوک‌ها</a></li><li><a href="units.php">واحدها</a></li></ul>
        <ul id="navigationPeople"><li class="navigation-divider">افراد و قراردادها</li><li><a href="persons.php">اشخاص</a></li><li><a href="contracts.php">قراردادها</a></li></ul>
        <ul id="navigationFinance"><li class="navigation-divider">مالی</li><li><a href="charges.php">شارژها</a></li></ul>
    </div>
</div>
<div class="header">
    <div class="header-logo"><a href="index.php"><img class="large-logo" src="<?= $tpl ?>assets/media/image/logo.png" alt="برجینو"><img class="small-logo" src="<?= $tpl ?>assets/media/image/logo-sm.png" alt="برجینو"><img class="dark-logo" src="<?= $tpl ?>assets/media/image/logo-dark.png" alt="برجینو"></a></div>
    <div class="header-body">
        <div class="header-body-left"><h3 class="page-title"><?= e($page_title) ?></h3><nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="index.php">برجینو</a></li><li class="breadcrumb-item active" aria-current="page"><?= e($page_title) ?></li></ol></nav></div>
        <div class="header-body-right"><ul class="navbar-nav"><li class="nav-item"><a href="#" class="nav-link"><i class="ti-search"></i></a></li><li class="nav-item"><a href="#settings" class="nav-link"><i class="ti-settings"></i></a></li><li class="nav-item"><a href="logout.php" class="nav-link"><i class="ti-power-off"></i></a></li></ul></div>
    </div>
</div>
<div class="main-content">
<?php if ($f): ?><div class="alert alert-info alert-with-border alert-dismissible fade show" role="alert"><?= e($f) ?><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div><?php endif; ?>
<?php
}
function page_footer(): void {
?>
</div>
<script src="https://raw.githubusercontent.com/parhamIH/Vira-online-shop/main/frontend/templateAdmin/vendors/bundle.js"></script>
<script src="https://raw.githubusercontent.com/parhamIH/Vira-online-shop/main/frontend/templateAdmin/assets/js/app.js"></script>
<div class="colors"><div class="bg-primary"></div><div class="bg-primary-bright"></div><div class="bg-secondary"></div><div class="bg-secondary-bright"></div><div class="bg-info"></div><div class="bg-info-bright"></div><div class="bg-success"></div><div class="bg-success-bright"></div><div class="bg-danger"></div><div class="bg-danger-bright"></div><div class="bg-warning"></div><div class="bg-warning-bright"></div></div>
</body></html>
<?php
}
