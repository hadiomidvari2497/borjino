<?php
require_once __DIR__.'/config.php';
require_login();

if (isset($_GET['delete'])) {
    $id=(int)$_GET['delete'];
    $st=$pdo->prepare('SELECT COUNT(*) FROM units WHERE block_id=?');
    $st->execute([$id]);
    if ((int)$st->fetchColumn()>0) { flash('این بلوک دارای واحد است و تا حذف واحدها قابل حذف نیست.'); redirect('blocks.php'); }
    $pdo->prepare('DELETE FROM blocks WHERE id=?')->execute([$id]);
    flash('بلوک حذف شد.');
    redirect('blocks.php');
}

if ($_SERVER['REQUEST_METHOD']==='POST') {
    check_csrf();
    $id=(int)post('id');
    $data=[
        (int)post('building_id'),
        trim(post('block_no')),
        trim(post('name')),
        max(0,(int)post('floor_count',0))
    ];
    if ($id) {
        $data[]=$id;
        $pdo->prepare('UPDATE blocks SET building_id=?,block_no=?,name=?,floor_count=? WHERE id=?')->execute($data);
        flash('بلوک ویرایش شد.');
    } else {
        $pdo->prepare('INSERT INTO blocks(building_id,block_no,name,floor_count) VALUES(?,?,?,?)')->execute($data);
        flash('بلوک اضافه شد.');
    }
    redirect('blocks.php');
}

$edit=null;
if (isset($_GET['edit'])) {
    $st=$pdo->prepare('SELECT * FROM blocks WHERE id=?');
    $st->execute([(int)$_GET['edit']]);
    $edit=$st->fetch();
}
$buildings=$pdo->query('SELECT id,name FROM buildings ORDER BY name')->fetchAll();
$rows=$pdo->query('SELECT b.*,g.name building_name,(SELECT COUNT(*) FROM units u WHERE u.block_id=b.id) unit_count FROM blocks b JOIN buildings g ON g.id=b.building_id ORDER BY b.id DESC')->fetchAll();
page_header('بلوک‌ها');
?>
<div class="content-header mb-4">
    <div><h4 class="mb-1">مدیریت بلوک‌ها</h4><p class="text-muted mb-0">تعداد طبقات فقط در سطح بلوک تعریف می‌شود.</p></div>
    <a class="btn btn-primary" href="blocks.php?new=1"><i class="ti-plus ml-1"></i> بلوک جدید</a>
</div>

<?php if(isset($_GET['new'])||$edit): ?>
<div class="card mb-4"><div class="card-body">
    <h6 class="card-title mb-4"><?= $edit ? 'ویرایش بلوک' : 'ایجاد بلوک' ?></h6>
    <form method="post">
        <?=csrf_field()?>
        <?php if($edit): ?><input type="hidden" name="id" value="<?=$edit['id']?>"><?php endif; ?>
        <div class="row">
            <div class="col-md-3 form-group"><label>ساختمان</label><select class="form-control" name="building_id" required><?php foreach($buildings as $b): ?><option value="<?=$b['id']?>" <?=($edit['building_id']??'')==$b['id']?'selected':''?>><?=e($b['name'])?></option><?php endforeach; ?></select></div>
            <div class="col-md-2 form-group"><label>شماره بلوک</label><input class="form-control" name="block_no" required value="<?=e($edit['block_no']??'')?>"></div>
            <div class="col-md-4 form-group"><label>نام بلوک</label><input class="form-control" name="name" required value="<?=e($edit['name']??'')?>"></div>
            <div class="col-md-3 form-group"><label>تعداد طبقات</label><input class="form-control" type="number" min="0" name="floor_count" required value="<?=$edit['floor_count']??0?>"></div>
        </div>
        <button class="btn btn-primary">ذخیره</button> <a class="btn btn-outline-secondary" href="blocks.php">انصراف</a>
    </form>
</div></div>
<?php endif; ?>

<div class="card"><div class="card-body"><div class="table-responsive"><table class="table table-hover mb-0">
<thead><tr><th>شماره</th><th>نام بلوک</th><th>ساختمان</th><th>طبقات</th><th>تعداد واحد</th><th>عملیات</th></tr></thead>
<tbody><?php foreach($rows as $r): ?><tr>
<td><?=e($r['block_no'])?></td><td><?=e($r['name'])?></td><td><?=e($r['building_name'])?></td><td><?=$r['floor_count']?></td><td><?=$r['unit_count']?></td>
<td><a class="btn btn-sm btn-outline-primary" href="blocks.php?edit=<?=$r['id']?>">ویرایش</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف شود؟')" href="blocks.php?delete=<?=$r['id']?>">حذف</a></td>
</tr><?php endforeach; ?></tbody>
</table></div></div></div>
<?php page_footer(); ?>
