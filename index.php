<?php
require_once __DIR__.'/config.php';
require_permission('dashboard','view');
page_header('داشبورد');

$counts=[];
foreach([
    'buildings'=>['ساختمان','ti-home','bg-warning','buildings.php'],
    'blocks'=>['بلوک','ti-layout-grid2','bg-info','blocks.php'],
    'units'=>['واحد','ti-layout-grid3','bg-success','units.php'],
    'persons'=>['شخص','ti-user','bg-primary','persons.php'],
    'contracts'=>['قرارداد','ti-files','bg-danger','contracts.php']
] as $table=>$meta){
    $counts[$table]=[
        'label'=>$meta[0],
        'icon'=>$meta[1],
        'color'=>$meta[2],
        'url'=>$meta[3],
        'count'=>(int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn()
    ];
}
?>
<div class="container-fluid">
    <div class="row align-items-center mb-3">
        <div class="col">
            <h4 class="mb-1">نمای کلی سامانه</h4>
            <p class="text-muted mb-0">مدیریت ساختمان، واحدها، ساکنان و امور مالی در یک نگاه</p>
        </div>
    </div>

    <div class="row">
        <?php foreach($counts as $item): ?>
        <div class="col-12 col-sm-6 col-lg-4 col-xl mb-3">
            <a href="<?= e($item['url']) ?>" class="card borjino-kpi text-decoration-none h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-block <?= e($item['color']) ?> text-white ml-3">
                        <i class="<?= e($item['icon']) ?>"></i>
                    </div>
                    <div>
                        <div class="text-muted small mb-1"><?= e($item['label']) ?></div>
                        <div class="kpi-value"><?= $item['count'] ?></div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="borjino-section-title">شروع سریع</h5>
                    <span class="text-muted small">دسترسی سریع به بخش‌های اصلی</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $quick=[
                            ['buildings','ساختمان‌ها','buildings.php','ti-home'],
                            ['blocks','بلوک‌ها','blocks.php','ti-layout-grid2'],
                            ['units','واحدها','units.php','ti-layout-grid3'],
                            ['persons','اشخاص','persons.php','ti-user'],
                            ['contracts','قراردادها','contracts.php','ti-files'],
                            ['charges','شارژها','charges.php','ti-wallet'],
                        ];
                        foreach($quick as $q):
                            if(!has_permission($q[0],'view')) continue;
                        ?>
                        <div class="col-6 col-md-4 mb-3">
                            <a href="<?= e($q[2]) ?>" class="btn btn-light btn-block py-3">
                                <i class="<?= e($q[3]) ?> ml-2"></i><?= e($q[1]) ?>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header"><h5 class="borjino-section-title">کاربر جاری</h5></div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md avatar-state-success ml-3">
                            <span class="avatar-title rounded-circle bg-primary text-white">
                                <?= e(mb_substr(current_user()['full_name'] ?: current_user()['username'],0,1,'UTF-8')) ?>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1"><?= e(current_user()['full_name'] ?: current_user()['username']) ?></h6>
                            <span class="text-muted small">@<?= e(current_user()['username']) ?></span>
                        </div>
                    </div>
                    <hr>
                    <a href="logout.php" class="btn btn-outline-danger btn-block">
                        <i class="ti-power-off ml-2"></i>خروج از حساب
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php page_footer(); ?>
