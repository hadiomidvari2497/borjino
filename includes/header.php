<?php
function page_header(string $title='برجینو'): void {
    $u = current_user();
    $f = flash();
    $page_title = $title;
    $current_page = basename($_SERVER['PHP_SELF']);

    $menu = [
        'dashboard'=>['title'=>'داشبورد','icon'=>'ti-pie-chart','items'=>[['dashboard','view','index.php','داشبورد']]],
        'building'=>['title'=>'مدیریت ساختمان','icon'=>'ti-home','items'=>[
            ['buildings','view','buildings.php','ساختمان‌ها'],['blocks','view','blocks.php','بلوک‌ها'],['units','view','units.php','واحدها']]],
        'people'=>['title'=>'مدیریت افراد','icon'=>'ti-user','items'=>[
            ['personnel','view','personnel.php','پرسنل'],['persons','view','persons.php','اشخاص'],['memberships','view','memberships.php','عضویت‌ها'],['contracts','view','contracts.php','قراردادها']]],
        'finance'=>['title'=>'امور مالی','icon'=>'ti-wallet','items'=>[
            ['costs','view','costs.php','هزینه‌ها'],['charges','view','charges.php','شارژها'],['payments','view','payments.php','پرداخت‌ها'],['charge_settings','view','charge_settings.php','تنظیمات شارژ']]],
        'reports'=>['title'=>'گزارش‌ها','icon'=>'ti-bar-chart','items'=>[['reports','view','reports.php','گزارش‌ها']]],
        'system'=>['title'=>'مدیریت سیستم','icon'=>'ti-settings','items'=>[
            ['users','view','users.php','کاربران'],['access_groups','view','access_groups.php','گروه‌های دسترسی']]],
    ];
    $visibleMenu=[];
    foreach($menu as $key=>$section){
        $items=array_values(array_filter($section['items'],static fn($item)=>has_permission($item[0],$item[1])));
        if($items)$visibleMenu[$key]=['title'=>$section['title'],'icon'=>$section['icon'],'items'=>$items];
    }
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($page_title) ?> - برجینو</title>
<link rel="shortcut icon" href="assets/media/image/favicon.svg">
<link rel="stylesheet" href="assets/css/vendor/bundle.css">
<link rel="stylesheet" href="assets/css/app.css">
<link rel="stylesheet" href="assets/css/borjino.css">
<link rel="stylesheet" href="assets/css/themify-icons.css">
</head>
<body class="borjino-nextable">
<div class="borjino-mobile-backdrop" id="borjinoBackdrop"></div>
<aside class="borjino-sidebar" id="borjinoSidebar">
    <div class="borjino-brand"><a href="index.php"><span class="borjino-brand-mark">ب</span><span>برجینو</span></a></div>
    <div class="borjino-side-scroll">
        <div class="borjino-side-title">داشبورد</div>
        <?php foreach($visibleMenu as $key=>$section): ?>
            <div class="borjino-menu-group">
                <button type="button" class="borjino-menu-heading" data-target="menu-<?= e($key) ?>">
                    <span><i class="<?= e($section['icon']) ?>"></i><?= e($section['title']) ?></span><i class="ti-angle-down"></i>
                </button>
                <div id="menu-<?= e($key) ?>" class="borjino-submenu">
                <?php foreach($section['items'] as $item): ?>
                    <a href="<?= e($item[2]) ?>" class="<?= basename($_SERVER['PHP_SELF'])===$item[2]?'active':'' ?>"><?= e($item[3]) ?></a>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <nav class="borjino-blue-rail" aria-label="انتخاب اصلی">
        <a href="index.php" class="<?= $current_page==='index.php'?'active':'' ?>" title="داشبورد"><i class="ti-pie-chart"></i></a>
        <a href="buildings.php" class="<?= in_array($current_page,['buildings.php','blocks.php','units.php'],true)?'active':'' ?>" title="مدیریت ساختمان"><i class="ti-home"></i></a>
        <a href="persons.php" class="<?= in_array($current_page,['personnel.php','persons.php','memberships.php','contracts.php'],true)?'active':'' ?>" title="مدیریت افراد"><i class="ti-user"></i></a>
        <a href="payments.php" class="<?= in_array($current_page,['costs.php','charges.php','payments.php','charge_settings.php'],true)?'active':'' ?>" title="امور مالی"><i class="ti-wallet"></i></a>
        <a href="reports.php" class="<?= $current_page==='reports.php'?'active':'' ?>" title="گزارش‌ها"><i class="ti-bar-chart"></i></a>
        <a href="users.php" class="<?= in_array($current_page,['users.php','access_groups.php'],true)?'active':'' ?>" title="مدیریت سیستم"><i class="ti-settings"></i></a>
    </nav>
    <div class="borjino-rail-bottom">
        <?php if(has_permission('charge_settings','view')): ?><a href="charge_settings.php" title="تنظیمات"><i class="ti-settings"></i></a><?php endif; ?>
        <button type="button" title="حساب کاربری" data-open-panel><i class="ti-user"></i></button>
    </div>
    <div class="borjino-sidebar-summary">
        <div class="borjino-side-title">خلاصه</div>
        <div class="borjino-summary-row"><span class="borjino-summary-icon orange">▥</span><div><b>مدیریت</b><small>سامانه ساختمان</small></div></div>
        <div class="borjino-summary-row"><span class="borjino-summary-icon green">✓</span><div><b>فعال</b><small>وضعیت سامانه</small></div></div>
    </div>
</aside>

<header class="borjino-topbar">
    <div class="borjino-top-right">
        <button class="borjino-mobile-menu" id="borjinoMenuButton" type="button"><i class="ti-menu"></i></button>
        <div>
            <h1><?= e($page_title) ?></h1>
            <div class="borjino-breadcrumb"><a href="index.php">برجینو</a><span>/</span><b><?= e($page_title) ?></b></div>
        </div>
    </div>
    <div class="borjino-toolbar">
        <div class="borjino-search"><i class="ti-search"></i><input type="search" placeholder="جستجو"></div>
        <button class="borjino-icon-btn" type="button" title="اعلان‌ها"><i class="ti-bell"></i><span class="dot"></span></button>
        <button class="borjino-icon-btn" type="button" title="افزودن"><i class="ti-plus"></i></button>
        <a class="borjino-user" href="#borjinoUser"><span><?= e(mb_substr($u['full_name']??$u['username']??'ب',0,1,'UTF-8')) ?></span></a>
    </div>
</header>

<main class="borjino-main">
<?php if($f): ?><div class="alert alert-info borjino-alert"><?= e($f) ?><button type="button" class="close" data-dismiss="alert">&times;</button></div><?php endif; ?>
<?php
}

function page_footer(): void {
?>
</main>
<div class="borjino-user-panel" id="borjinoUser">
    <div class="borjino-user-panel-card">
        <button class="borjino-panel-close" type="button" data-close-panel>&times;</button>
        <div class="borjino-profile-avatar"><?= e(mb_substr(current_user()['full_name']??current_user()['username']??'ب',0,1,'UTF-8')) ?></div>
        <h3><?= e(current_user()['full_name']??current_user()['username']??'کاربر سامانه') ?></h3>
        <p>@<?= e(current_user()['username']??'') ?></p>
        <a href="logout.php" class="borjino-logout"><i class="ti-power-off"></i> خروج از حساب</a>
    </div>
</div>
<script src="assets/js/vendor/bundle.js"></script>
<script src="assets/js/app.js"></script>
<script>
document.querySelectorAll('.borjino-menu-heading').forEach(function(b){b.addEventListener('click',function(){document.getElementById(b.dataset.target).classList.toggle('open');b.classList.toggle('open')})});
(function(){
    var current=<?=json_encode($current_page,JSON_UNESCAPED_UNICODE)?>;
    document.querySelectorAll('.borjino-menu-group').forEach(function(group){
        var active=group.querySelector('.borjino-submenu a.active');
        if(active){
            var submenu=group.querySelector('.borjino-submenu');
            var heading=group.querySelector('.borjino-menu-heading');
            if(submenu)submenu.classList.add('open');
            if(heading)heading.classList.add('open');
        }
    });
})();
var mb=document.getElementById('borjinoMenuButton'),sb=document.getElementById('borjinoSidebar'),bd=document.getElementById('borjinoBackdrop');
function toggleMenu(){sb.classList.toggle('open');bd.classList.toggle('open')}
if(mb)mb.addEventListener('click',toggleMenu); if(bd)bd.addEventListener('click',toggleMenu);
document.querySelectorAll('[data-close-panel]').forEach(function(b){b.addEventListener('click',function(){document.getElementById('borjinoUser').classList.remove('open')})});
document.querySelector('.borjino-user')?.addEventListener('click',function(e){e.preventDefault();document.getElementById('borjinoUser').classList.add('open')});
document.querySelector('[data-open-panel]')?.addEventListener('click',function(){document.getElementById('borjinoUser').classList.add('open')});
</script>
</body>
</html>
<?php
}
