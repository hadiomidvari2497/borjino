<?php
require_once __DIR__.'/config.php';
require_permission('dashboard','view');
page_header('داشبورد');
$counts=[];
foreach(['buildings'=>'ساختمان','blocks'=>'بلوک','units'=>'واحد','persons'=>'شخص','contracts'=>'قرارداد'] as $table=>$label){
    $counts[$table]=['label'=>$label,'count'=>(int)$pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn()];
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">خوش آمدید <?= e(current_user()['full_name'] ?: current_user()['username']) ?></h4>
                    <p class="text-muted mb-0">نمای کلی سامانه مدیریت ساختمان برجینو</p>
                </div>
            </div>
        </div>
        <?php
        $icons=['buildings'=>'ti-home','blocks'=>'ti-layout-grid2','units'=>'ti-layout-grid3','persons'=>'ti-user','contracts'=>'ti-files'];
        $colors=['buildings'=>'bg-warning','blocks'=>'bg-info','units'=>'bg-success','persons'=>'bg-primary','contracts'=>'bg-danger'];
        foreach($counts as $key=>$item):
        ?>
        <div class="col-md-6 col-lg-4 col-xl-2 mt-3">
            <a href="<?= $key ?>.php" class="card text-decoration-none">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-block <?= $colors[$key] ?> text-white mr-3"><i class="<?= $icons[$key] ?>"></i></div>
                    <div><h6 class="font-size-13 line-height-22 primary-font m-b-5"><?= e($item['label']) ?></h6><h4 class="m-b-0 primary-font font-weight-bold line-height-30"><?= $item['count'] ?></h4></div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
        <div class="col-md-8 mt-3">
            <div class="card">
                <div class="card-header">شروع سریع</div>
                <div class="card-body">
                    <p class="text-muted">از منوی سمت راست ساختمان، بلوک، واحد، اشخاص، قراردادها و شارژ را مدیریت کنید.</p>
                    <div class="d-flex flex-wrap">
                        <a class="btn btn-primary ml-2 mb-2" href="buildings.php">مدیریت ساختمان‌ها</a>
                        <a class="btn btn-outline-primary ml-2 mb-2" href="blocks.php">مدیریت بلوک‌ها</a>
                        <a class="btn btn-outline-success ml-2 mb-2" href="units.php">مدیریت واحدها</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php page_footer(); ?>
