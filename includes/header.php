<?php
function page_header(string $title='برجینو'): void {
    $u = current_user();
    $f = flash();
    $page_title = $title;
    $tpl = 'https://raw.githubusercontent.com/parhamIH/Vira-online-shop/main/frontend/templateAdmin/';

    $menu = [
        'dashboard' => [
            'title'=>'داشبورد',
            'icon'=>'ti-pie-chart',
            'items'=>[['dashboard','view','index.php','داشبورد اصلی']]
        ],
        'building' => [
            'title'=>'مدیریت ساختمان',
            'icon'=>'ti-home',
            'items'=>[
                ['buildings','view','buildings.php','ساختمان‌ها'],
                ['blocks','view','blocks.php','بلوک‌ها'],
                ['units','view','units.php','واحدها'],
            ]
        ],
        'people' => [
            'title'=>'مدیریت افراد',
            'icon'=>'ti-user',
            'items'=>[
                ['personnel','view','personnel.php','پرسنل'],
                ['persons','view','persons.php','اشخاص'],
                ['memberships','view','memberships.php','عضویت‌ها'],
                ['contracts','view','contracts.php','قراردادها'],
            ]
        ],
        'finance' => [
            'title'=>'امور مالی',
            'icon'=>'ti-wallet',
            'items'=>[
                ['costs','view','costs.php','هزینه‌ها'],
                ['charges','view','charges.php','شارژها'],
                ['payments','view','payments.php','پرداخت‌ها'],
                ['charge_settings','view','charge_settings.php','تنظیمات شارژ'],
            ]
        ],
        'system' => [
            'title'=>'مدیریت سیستم',
            'icon'=>'ti-settings',
            'items'=>[
                ['users','view','users.php','کاربران'],
                ['access_groups','view','access_groups.php','گروه‌های دسترسی'],
            ]
        ],
    ];
    $visibleMenu = [];
    foreach ($menu as $key=>$section) {
        $items = array_values(array_filter($section['items'], static fn($item) => has_permission($item[0], $item[1])));
        if ($items) $visibleMenu[$key] = ['title'=>$section['title'],'icon'=>$section['icon'],'items'=>$items];
    }
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
    <link rel="stylesheet" href="assets/css/borjino.css" type="text/css">
</head>
<body>
<div class="page-loader"><div class="spinner-border"></div></div>

<div class="sidebar" id="userProfile">
    <div class="text-center p-4">
        <figure class="avatar avatar-state-success avatar-lg mb-4">
            <img src="<?= $tpl ?>assets/media/image/avatar.jpg" class="rounded-circle" alt="پروفایل">
        </figure>
        <h4 class="text-primary m-b-10"><?= e($u['full_name'] ?? 'کاربر سامانه') ?></h4>
        <p class="text-muted mb-0"><?= e($u['username'] ?? '') ?></p>
    </div>
    <hr class="m-0">
    <div class="p-4">
        <p class="text-muted mb-0">مدیریت ساختمان، ساکنان و امور مالی</p>
    </div>
</div>

<div class="sidebar" id="settings">
    <header><i class="ti-settings"></i> تنظیمات</header>
    <div class="p-4">
        <p class="text-muted">تنظیمات سامانه از بخش‌های مدیریتی قابل دسترسی است.</p>
    </div>
</div>

<div class="navigation">
    <div class="navigation-icon-menu">
        <ul>
            <?php $first=true; foreach ($visibleMenu as $key=>$section): ?>
                <li class="<?= $first?'active':'' ?>" data-toggle="tooltip" title="<?= e($section['title']) ?>">
                    <a href="#navigation<?= e(ucfirst($key)) ?>"><i class="icon <?= e($section['icon']) ?>"></i></a>
                </li>
            <?php $first=false; endforeach; ?>
        </ul>
        <ul>
            <li data-toggle="tooltip" title="پروفایل"><a href="#userProfile"><i class="icon ti-user"></i></a></li>
            <li data-toggle="tooltip" title="خروج"><a href="logout.php" class="go-to-page"><i class="icon ti-power-off"></i></a></li>
        </ul>
    </div>

    <div class="navigation-menu-body">
        <?php $firstSection=true; foreach ($visibleMenu as $key=>$section): ?>
            <ul id="navigation<?= e(ucfirst($key)) ?>" class="<?= $firstSection?'navigation-active':'' ?>">
                <li class="navigation-divider"><?= e($section['title']) ?></li>
                <?php foreach ($section['items'] as $item): ?>
                    <li>
                        <a href="<?= e($item[2]) ?>" class="<?= basename($_SERVER['PHP_SELF']) === $item[2] ? 'active' : '' ?>">
                            <?= e($item[3]) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php $firstSection=false; endforeach; ?>
    </div>
</div>

<div class="header">
    <div class="header-logo">
        <a href="index.php">
            <img class="large-logo" src="<?= $tpl ?>assets/media/image/logo.png" alt="برجینو">
            <img class="small-logo" src="<?= $tpl ?>assets/media/image/logo-sm.png" alt="برجینو">
            <img class="dark-logo" src="<?= $tpl ?>assets/media/image/logo-dark.png" alt="برجینو">
        </a>
    </div>
    <div class="header-body">
        <div class="header-body-left">
            <h3 class="page-title"><?= e($page_title) ?></h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">برجینو</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= e($page_title) ?></li>
                </ol>
            </nav>
        </div>
        <div class="header-body-right">
            <ul class="navbar-nav">
                <li class="nav-item"><a href="#userProfile" class="nav-link" title="پروفایل"><i class="ti-user"></i></a></li>
                <li class="nav-item"><a href="#settings" class="nav-link" title="تنظیمات"><i class="ti-settings"></i></a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link" title="خروج"><i class="ti-power-off"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<div class="main-content">
<?php if ($f): ?>
    <div class="alert alert-info alert-with-border alert-dismissible fade show" role="alert">
        <?= e($f) ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="بستن"><span>&times;</span></button>
    </div>
<?php endif; ?>
<?php
}

function page_footer(): void {
?>
</div>
<script src="https://raw.githubusercontent.com/parhamIH/Vira-online-shop/main/frontend/templateAdmin/vendors/bundle.js"></script>
<script src="https://raw.githubusercontent.com/parhamIH/Vira-online-shop/main/frontend/templateAdmin/assets/js/app.js"></script>
<div class="colors">
    <div class="bg-primary"></div><div class="bg-primary-bright"></div>
    <div class="bg-secondary"></div><div class="bg-secondary-bright"></div>
    <div class="bg-info"></div><div class="bg-info-bright"></div>
    <div class="bg-success"></div><div class="bg-success-bright"></div>
    <div class="bg-danger"></div><div class="bg-danger-bright"></div>
    <div class="bg-warning"></div><div class="bg-warning-bright"></div>
</div>
</body>
</html>
<?php
}
