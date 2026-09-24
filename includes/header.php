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
    <?php
    $active_section = 'dashboard';
    foreach($visibleMenu as $key=>$section){
        foreach($section['items'] as $item){
            if($current_page === $item[2]){$active_section=$key;break 2;}
        }
    }
    ?>
    <div class="borjino-side-scroll">
        <div class="borjino-side-title">منوی <?= e($visibleMenu[$active_section]['title'] ?? 'اصلی') ?></div>
        <?php foreach($visibleMenu as $key=>$section): ?>
            <div class="borjino-menu-group <?= $key===$active_section?'active':'' ?>" data-section="<?= e($key) ?>">
                <button type="button" class="borjino-menu-heading" data-target="menu-<?= e($key) ?>">
                    <span><i class="<?= e($section['icon']) ?>"></i><?= e($section['title']) ?></span><i class="ti-angle-down"></i>
                </button>
                <div id="menu-<?= e($key) ?>" class="borjino-submenu">
                <?php foreach($section['items'] as $item): ?>
                    <a href="<?= e($item[2]) ?>" class="<?= $current_page===$item[2]?'active':'' ?>"><?= e($item[3]) ?></a>
                <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <nav class="borjino-blue-rail" aria-label="منوی اصلی">
        <?php foreach($visibleMenu as $key=>$section): ?>
            <button type="button" class="<?= $key===$active_section?'active':'' ?>" data-section-target="<?= e($key) ?>" title="<?= e($section['title']) ?>">
                <i class="<?= e($section['icon']) ?>"></i>
            </button>
        <?php endforeach; ?>
        <div class="borjino-rail-summary" aria-label="خلاصه سامانه">
            <div class="borjino-rail-summary-title">خلاصه</div>
            <div class="borjino-rail-summary-item"><span class="borjino-summary-icon orange">▥</span><b>مدیریت</b></div>
            <div class="borjino-rail-summary-item"><span class="borjino-summary-icon green">✓</span><b>فعال</b></div>
        </div>
    </nav>
    <div class="borjino-rail-bottom">
        <button type="button" title="حساب کاربری" data-open-panel><i class="ti-user"></i></button>
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
        <div class="borjino-search"><i class="ti-search"></i><input type="search" placeholder="جستجو در سامانه" aria-label="جستجو"></div>
        <button class="borjino-icon-btn" type="button" title="اعلان‌ها" aria-label="اعلان‌ها"><i class="ti-bell"></i><span class="dot"></span></button>
        <div class="borjino-quick-add">
            <button class="borjino-icon-btn" type="button" title="افزودن سریع" aria-label="افزودن سریع" data-quick-add><i class="ti-plus"></i></button>
            <div class="borjino-quick-menu" id="borjinoQuickMenu">
                <?php if(has_permission('buildings','create')): ?><a href="buildings.php?new=1"><i class="ti-home"></i>ساختمان جدید</a><?php endif; ?>
                <?php if(has_permission('units','create')): ?><a href="units.php?new=1"><i class="ti-layout-grid3"></i>واحد جدید</a><?php endif; ?>
                <?php if(has_permission('persons','create')): ?><a href="persons.php?new=1"><i class="ti-user"></i>شخص جدید</a><?php endif; ?>
                <?php if(has_permission('charges','create')): ?><a href="charges.php?new=1"><i class="ti-wallet"></i>شارژ جدید</a><?php endif; ?>
            </div>
        </div>
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
(function(){
    var current=<?=json_encode($current_page,JSON_UNESCAPED_UNICODE)?>;
    function activateSection(key){
        document.querySelectorAll('.borjino-menu-group').forEach(function(group){
            var active=group.dataset.section===key;
            group.classList.toggle('active',active);
            var submenu=group.querySelector('.borjino-submenu');
            var heading=group.querySelector('.borjino-menu-heading');
            if(submenu)submenu.classList.toggle('open',active);
            if(heading)heading.classList.toggle('open',active);
        });
        document.querySelectorAll('.borjino-blue-rail [data-section-target]').forEach(function(btn){
            btn.classList.toggle('active',btn.dataset.sectionTarget===key);
        });
        var title=document.querySelector('.borjino-side-title');
        var group=document.querySelector('.borjino-menu-group[data-section="'+key+'"]');
        var heading=group&&group.querySelector('.borjino-menu-heading');
        if(title&&heading)title.textContent='منوی '+heading.textContent.replace(/\s*⌄?\s*$/,'').trim();
    }
    document.querySelectorAll('.borjino-blue-rail [data-section-target]').forEach(function(btn){
        btn.addEventListener('click',function(){activateSection(btn.dataset.sectionTarget)});
    });
    document.querySelectorAll('.borjino-menu-heading').forEach(function(b){
        b.addEventListener('click',function(){
            var group=b.closest('.borjino-menu-group');
            activateSection(group.dataset.section);
        });
    });
    activateSection(<?=json_encode($active_section,JSON_UNESCAPED_UNICODE)?>);
})();
var mb=document.getElementById('borjinoMenuButton'),sb=document.getElementById('borjinoSidebar'),bd=document.getElementById('borjinoBackdrop');
function toggleMenu(){sb.classList.toggle('open');bd.classList.toggle('open')}
if(mb)mb.addEventListener('click',toggleMenu); if(bd)bd.addEventListener('click',toggleMenu);
document.querySelectorAll('[data-close-panel]').forEach(function(b){b.addEventListener('click',function(){document.getElementById('borjinoUser').classList.remove('open')})});
document.querySelector('.borjino-user')?.addEventListener('click',function(e){e.preventDefault();document.getElementById('borjinoUser').classList.add('open')});
document.querySelector('[data-open-panel]')?.addEventListener('click',function(){document.getElementById('borjinoUser').classList.add('open')});
var qa=document.querySelector('[data-quick-add]'),qm=document.getElementById('borjinoQuickMenu');
if(qa&&qm){qa.addEventListener('click',function(e){e.stopPropagation();qm.classList.toggle('open')});document.addEventListener('click',function(){qm.classList.remove('open')})}
</script>
</body>
</html>
<?php
}
