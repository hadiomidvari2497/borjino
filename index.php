<?php
require_once __DIR__.'/config.php';
require_permission('dashboard','view');
page_header('داشبورد');

$counts=[];
foreach([
 'buildings'=>['ساختمان','ti-home','buildings.php'],
 'blocks'=>['بلوک','ti-layout-grid2','blocks.php'],
 'units'=>['واحد','ti-layout-grid3','units.php'],
 'persons'=>['شخص','ti-user','persons.php'],
 'contracts'=>['قرارداد','ti-files','contracts.php']
] as $table=>$meta){
 $counts[$table]=['label'=>$meta[0],'icon'=>$meta[1],'url'=>$meta[2],'count'=>(int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn()];
}
?>
<div class="borjino-dashboard">
<section class="b-card"><div class="b-sales">
<div class="b-sales-title">نمای کلی سامانه</div>
<div class="b-arrows"><button class="b-arrow" type="button">‹</button><button class="b-arrow" type="button">›</button></div>
<div class="b-network">
<?php $net=[['ساختمان‌ها',$counts['buildings']['count'],'green','⌂'],['واحدها',$counts['units']['count'],'blue','▦'],['اشخاص',$counts['persons']['count'],'red','●'],['قراردادها',$counts['contracts']['count'],'orange','★']]; foreach($net as $n): ?>
<a class="b-net <?= $n[2] ?>" href="<?= e($counts[strtolower($n[0])]['url']??'#') ?>" style="text-decoration:none;color:inherit">
<div class="b-net-head"><span><?= e($n[0]) ?></span><span class="b-net-num"><?= e((string)$n[1]) ?></span><span class="b-net-icon"><?= e($n[3]) ?></span></div><div class="b-net-bar"><i style="width:<?= max(18,min(92,($n[1]%100)+18)) ?>%"></i></div>
</a>
<?php endforeach; ?>
</div></div></section>

<div class="b-dashboard-grid">
<section class="b-card"><div class="b-card-head">گزارشات <span class="text-muted small">وضعیت داده‌های سامانه</span></div><div class="b-card-body">
<div class="b-stat-row">
<?php foreach(array_slice($counts,0,3) as $c): ?><div class="b-stat"><div class="v"><?= e((string)$c['count']) ?></div><div class="l"><?= e($c['label']) ?></div></div><?php endforeach; ?>
</div>
<div class="b-bars"><?php foreach(array_values($counts) as $i=>$c): ?><div class="b-bargrp"><i style="height:<?= 18+(($c['count']*13+$i*11)%70) ?>%"></i><i style="height:<?= 30+(($c['count']*7+$i*9)%60) ?>%"></i></div><?php endforeach; ?></div>
<div class="b-labels"><?php foreach($counts as $c): ?><span><?= e($c['label']) ?></span><?php endforeach; ?></div>
</div></section>

<section class="b-card"><div class="b-card-head">توزیع اطلاعات</div><div class="b-card-body">
<div class="b-regions">
<?php $regions=[['ساختمان',max(12,min(90,20+$counts['buildings']['count']*8))],['واحد',max(12,min(90,20+$counts['units']['count']*5))],['اشخاص',max(12,min(90,20+$counts['persons']['count']*4))],['قرارداد',max(12,min(90,20+$counts['contracts']['count']*7))]]; foreach($regions as $r): ?><div class="b-region"><span><?= e($r[0]) ?></span><i style="width:<?= $r[1] ?>%"></i></div><?php endforeach; ?>
</div>
<div class="b-totals"><span><?= $counts['buildings']['count'] ?> ساختمان</span><span><?= $counts['units']['count'] ?> واحد</span><span><?= $counts['persons']['count'] ?> شخص</span></div>
</div></section>
</div>

<div class="b-kpi-grid">
<section class="b-card b-kpi blue"><div class="b-card-head">تعداد اشخاص</div><div class="b-card-body"><div class="b-mini-bars"><?php for($i=0;$i<8;$i++): ?><i style="--h:<?= 25+(($counts['persons']['count']+$i*17)%65) ?>%"></i><?php endfor; ?></div><div class="b-money"><?= number_format($counts['persons']['count']) ?></div><div class="b-sub">ثبت‌شده در سامانه</div></div></section>
<section class="b-card b-kpi"><div class="b-card-head">تعداد واحدها</div><div class="b-card-body"><div class="b-mini-bars"><?php for($i=0;$i<8;$i++): ?><i style="--h:<?= 30+(($counts['units']['count']+$i*13)%60) ?>%"></i><?php endfor; ?></div><div class="b-money"><?= number_format($counts['units']['count']) ?></div><div class="b-sub">واحد قابل مدیریت</div></div></section>
</div>

<div class="b-dashboard-grid">
<section class="b-card"><div class="b-card-head">فروش محصولات</div><div class="b-card-body"><div class="b-product-big"><?= number_format($counts['buildings']['count']+$counts['units']['count']+$counts['persons']['count']) ?></div><div class="b-product-stats"><div style="text-align:center;font-weight:800">ساختمان<div class="b-money"><?= $counts['buildings']['count'] ?></div><div class="b-progress"><i style="width:70%"></i></div></div><div style="text-align:center;font-weight:800">قرارداد<div class="b-money"><?= $counts['contracts']['count'] ?></div><div class="b-progress"><i style="width:45%"></i></div></div></div></div></section>
<section class="b-card"><div class="b-card-head">وضعیت سامانه</div><div class="b-card-body"><div style="display:flex;align-items:center;justify-content:center;min-height:210px;flex-direction:column;gap:12px"><div style="width:190px;height:190px;border-radius:50%;background:conic-gradient(#5967df 0 72%,#e5e6e7 72%);position:relative"><div style="position:absolute;inset:43px;background:#fff;border-radius:50%;display:grid;place-items:center;font-size:25px;font-weight:900">فعال</div></div><b>سامانه برجینو آماده مدیریت است</b></div></div></section>
</div>
</div>
<?php page_footer(); ?>