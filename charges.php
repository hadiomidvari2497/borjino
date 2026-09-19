<?php
require_once __DIR__ . '/config.php';
require_page_permission('charges');
require_once __DIR__ . '/includes/charge_engine.php';

$settings = get_charge_settings($pdo);
$units = $pdo->query(
    'SELECT id, building_id, unit_no, area, parking_count, storage_count
     FROM units ORDER BY building_id, block_id, unit_no'
)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action = post('action');
    $period = trim(post('period'));
    $title = trim(post('title')) ?: 'شارژ ماهانه';
    $dueDate = post('due_date') ?: null;

    try {
        charge_period_parts($period);

        if ($action !== 'issue') {
            throw new RuntimeException('عملیات نامعتبر است.');
        }
        if (!$units) {
            throw new RuntimeException('هیچ واحدی برای صدور شارژ وجود ندارد.');
        }

        $pdo->beginTransaction();
        $insert = $pdo->prepare(
            'INSERT INTO charges
             (unit_id,title,period,amount,calculation_method,calculation_details,due_date,status,notes)
             VALUES (?,?,?,?,?,?,?,'unpaid',NULL)'
        );

        $issued = 0;
        $skipped = 0;
        foreach ($units as $unit) {
            $exists = $pdo->prepare('SELECT id FROM charges WHERE unit_id=? AND period=? LIMIT 1');
            $exists->execute([(int)$unit['id'], $period]);
            if ($exists->fetchColumn()) {
                $skipped++;
                continue;
            }

            $calculation = calculate_unit_charge($pdo, $unit, $period, $settings);
            $insert->execute([
                (int)$unit['id'],
                $title,
                $period,
                $calculation['amount'],
                $calculation['method'],
                json_encode($calculation['details'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                $dueDate
            ]);
            $issued++;
        }

        $pdo->commit();
        log_activity('create', 'charges', null, sprintf('صدور شارژ دوره %s: %d صادر، %d قبلی', $period, $issued, $skipped));
        flash(sprintf('صدور شارژ انجام شد؛ %d مورد صادر شد و %d مورد از قبل وجود داشت.', $issued, $skipped));
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        flash('صدور شارژ انجام نشد: ' . $e->getMessage());
    }
    redirect('charges.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('action') === 'delete') {
    check_csrf();
    $id = (int)post('id');
    $st = $pdo->prepare('DELETE FROM charges WHERE id=?');
    $st->execute([$id]);
    log_activity('delete', 'charges', $id, 'حذف شارژ');
    flash('شارژ حذف شد.');
    redirect('charges.php');
}

$rows = $pdo->query(
    'SELECT c.*, u.unit_no, b.name AS building_name
     FROM charges c
     JOIN units u ON u.id=c.unit_id
     JOIN buildings b ON b.id=u.building_id
     ORDER BY c.id DESC'
)->fetchAll();

page_header('صدور شارژ');
?>
<div class="content-header mb-4">
    <div>
        <h4 class="mb-1">صدور شارژ</h4>
        <p class="text-muted mb-0">مبلغ هر واحد از روش انتخاب‌شده در تنظیمات شارژ محاسبه می‌شود.</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="post">
            <?=csrf_field()?>
            <input type="hidden" name="action" value="issue">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>دوره شارژ</label>
                    <input class="form-control" name="period" required pattern="[0-9]{4}-[0-9]{2}" placeholder="YYYY-MM">
                </div>
                <div class="col-md-4 form-group">
                    <label>عنوان</label>
                    <input class="form-control" name="title" value="شارژ ماهانه" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>سررسید</label>
                    <input class="form-control" type="date" name="due_date">
                </div>
            </div>
            <div class="alert alert-info">
                روش فعال:
                <strong><?=e($settings['calculation_method'].' - '.charge_methods()[(int)$settings['calculation_method']])?></strong>
            </div>
            <button class="btn btn-primary" type="submit" onclick="return confirm('برای تمام واحدهای فاقد شارژ این دوره، شارژ صادر شود؟')">
                صدور شارژ
            </button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">شارژهای صادرشده</h5>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>ساختمان</th><th>واحد</th><th>دوره</th><th>روش</th><th>مبلغ</th><th>سررسید</th><th>وضعیت</th><th>عملیات</th></tr></thead>
                <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?=e($r['building_name'])?></td>
                        <td><?=e($r['unit_no'])?></td>
                        <td><?=e($r['period'])?></td>
                        <td><?=e(charge_methods()[(int)$r['calculation_method']] ?? '-')?></td>
                        <td><?=money($r['amount'])?></td>
                        <td><?=e($r['due_date'] ?? '-')?></td>
                        <td><?=e($r['status'])?></td>
                        <td>
                            <form method="post" style="display:inline" onsubmit="return confirm('حذف شود؟')"><?=csrf_field()?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?=$r['id']?>"><button class="btn btn-sm btn-outline-danger" type="submit">حذف</button></form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php page_footer(); ?>
