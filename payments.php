<?php
require_once __DIR__ . '/config.php';
require_page_permission('payments');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_csrf();
    $action = post('action');

    try {
        if ($action === 'create') {
            require_permission('payments', 'create');

            $chargeId = (int)post('charge_id');
            $amount = (float)post('amount');
            $paidAt = trim(post('paid_at')) ?: date('Y-m-d');
            $method = trim(post('method')) ?: 'cash';
            $referenceNo = trim(post('reference_no')) ?: null;
            $notes = trim(post('notes')) ?: null;

            if ($chargeId <= 0 || $amount <= 0) {
                throw new RuntimeException('شارژ و مبلغ پرداخت را به‌درستی وارد کنید.');
            }
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $paidAt)) {
                throw new RuntimeException('تاریخ پرداخت نامعتبر است.');
            }

            $pdo->beginTransaction();

            $st = $pdo->prepare(
                'SELECT c.*, u.id AS unit_id, u.financial_status
                 FROM charges c
                 JOIN units u ON u.id=c.unit_id
                 WHERE c.id=? FOR UPDATE'
            );
            $st->execute([$chargeId]);
            $charge = $st->fetch();

            if (!$charge) {
                throw new RuntimeException('شارژ موردنظر پیدا نشد.');
            }

            $paidSt = $pdo->prepare('SELECT COALESCE(SUM(amount),0) FROM payments WHERE charge_id=?');
            $paidSt->execute([$chargeId]);
            $paidBefore = (float)$paidSt->fetchColumn();
            $remaining = (float)$charge['amount'] - $paidBefore;

            if ($remaining <= 0) {
                throw new RuntimeException('این شارژ قبلاً به‌طور کامل پرداخت شده است.');
            }
            if ($amount > $remaining) {
                throw new RuntimeException('مبلغ پرداخت از مانده شارژ بیشتر است.');
            }

            $insert = $pdo->prepare(
                'INSERT INTO payments(charge_id,amount,paid_at,method,reference_no,notes)
                 VALUES(?,?,?,?,?,?)'
            );
            $insert->execute([$chargeId, $amount, $paidAt, $method, $referenceNo, $notes]);
            $paymentId = (int)$pdo->lastInsertId();

            $paidAfter = $paidBefore + $amount;
            $newStatus = $paidAfter >= (float)$charge['amount'] ? 'paid' : 'partial';

            $update = $pdo->prepare('UPDATE charges SET status=? WHERE id=?');
            $update->execute([$newStatus, $chargeId]);

            $balanceSt = $pdo->prepare(
                'SELECT
                    COALESCE(SUM(c.amount),0) AS total_charges,
                    COALESCE(SUM(p.paid_amount),0) AS total_paid
                 FROM charges c
                 LEFT JOIN (
                    SELECT charge_id, SUM(amount) AS paid_amount
                    FROM payments
                    GROUP BY charge_id
                 ) p ON p.charge_id=c.id
                 WHERE c.unit_id=?'
            );
            $balanceSt->execute([(int)$charge['unit_id']]);
            $balance = $balanceSt->fetch();

            $totalCharges = (float)$balance['total_charges'];
            $totalPaid = (float)$balance['total_paid'];
            $financialStatus = 'settled';
            if ($totalPaid < $totalCharges) {
                $financialStatus = 'debtor';
            }

            $unitUpdate = $pdo->prepare('UPDATE units SET financial_status=? WHERE id=?');
            $unitUpdate->execute([$financialStatus, (int)$charge['unit_id']]);

            $pdo->commit();

            log_activity(
                'create',
                'payments',
                $paymentId,
                sprintf('ثبت پرداخت %.2f برای شارژ %d', $amount, $chargeId)
            );
            flash('پرداخت با موفقیت ثبت شد.');
        } elseif ($action === 'delete') {
            require_permission('payments', 'delete');

            $paymentId = (int)post('id');
            if ($paymentId <= 0) {
                throw new RuntimeException('پرداخت نامعتبر است.');
            }

            $pdo->beginTransaction();

            $st = $pdo->prepare(
                'SELECT p.*, c.unit_id, c.amount AS charge_amount
                 FROM payments p
                 JOIN charges c ON c.id=p.charge_id
                 WHERE p.id=? FOR UPDATE'
            );
            $st->execute([$paymentId]);
            $payment = $st->fetch();

            if (!$payment) {
                throw new RuntimeException('پرداخت موردنظر پیدا نشد.');
            }

            $delete = $pdo->prepare('DELETE FROM payments WHERE id=?');
            $delete->execute([$paymentId]);

            $paidSt = $pdo->prepare('SELECT COALESCE(SUM(amount),0) FROM payments WHERE charge_id=?');
            $paidSt->execute([(int)$payment['charge_id']]);
            $paid = (float)$paidSt->fetchColumn();

            $status = $paid <= 0 ? 'unpaid' : ($paid >= (float)$payment['charge_amount'] ? 'paid' : 'partial');
            $update = $pdo->prepare('UPDATE charges SET status=? WHERE id=?');
            $update->execute([$status, (int)$payment['charge_id']]);

            $balanceSt = $pdo->prepare(
                'SELECT
                    COALESCE(SUM(c.amount),0) AS total_charges,
                    COALESCE(SUM(p.paid_amount),0) AS total_paid
                 FROM charges c
                 LEFT JOIN (
                    SELECT charge_id, SUM(amount) AS paid_amount
                    FROM payments
                    GROUP BY charge_id
                 ) p ON p.charge_id=c.id
                 WHERE c.unit_id=?'
            );
            $balanceSt->execute([(int)$payment['unit_id']]);
            $balance = $balanceSt->fetch();

            $financialStatus = ((float)$balance['total_paid'] < (float)$balance['total_charges']) ? 'debtor' : 'settled';
            $unitUpdate = $pdo->prepare('UPDATE units SET financial_status=? WHERE id=?');
            $unitUpdate->execute([$financialStatus, (int)$payment['unit_id']]);

            $pdo->commit();

            log_activity('delete', 'payments', $paymentId, 'حذف پرداخت و بازتنظیم وضعیت شارژ');
            flash('پرداخت حذف شد و وضعیت مالی بازتنظیم شد.');
        } else {
            throw new RuntimeException('عملیات نامعتبر است.');
        }
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        flash('عملیات پرداخت انجام نشد: ' . $e->getMessage());
    }

    redirect('payments.php');
}

$charges = $pdo->query(
    'SELECT c.id, c.title, c.period, c.amount, c.status,
            u.id AS unit_id, u.unit_no, b.name AS building_name,
            COALESCE((SELECT SUM(p.amount) FROM payments p WHERE p.charge_id=c.id),0) AS paid_amount
     FROM charges c
     JOIN units u ON u.id=c.unit_id
     JOIN buildings b ON b.id=u.building_id
     ORDER BY c.id DESC'
)->fetchAll();

$payments = $pdo->query(
    'SELECT p.*, c.title, c.period, c.amount AS charge_amount,
            u.unit_no, b.name AS building_name
     FROM payments p
     JOIN charges c ON c.id=p.charge_id
     JOIN units u ON u.id=c.unit_id
     JOIN buildings b ON b.id=u.building_id
     ORDER BY p.id DESC'
)->fetchAll();

page_header('پرداخت‌ها');
?>
<div class="content-header mb-4">
    <div>
        <h4 class="mb-1">ثبت پرداخت</h4>
        <p class="text-muted mb-0">ثبت و مدیریت پرداخت‌های مربوط به شارژهای صادرشده.</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="post">
            <?=csrf_field()?>
            <input type="hidden" name="action" value="create">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label>شارژ</label>
                    <select class="form-control" name="charge_id" required>
                        <option value="">انتخاب کنید</option>
                        <?php foreach ($charges as $c):
                            $remaining = max(0, (float)$c['amount'] - (float)$c['paid_amount']);
                            if ($remaining <= 0) continue;
                        ?>
                            <option value="<?=$c['id']?>">
                                <?=e($c['building_name'].' / واحد '.$c['unit_no'].' / '.$c['period'].' / مانده '.money($remaining))?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>مبلغ پرداخت</label>
                    <input class="form-control" type="number" min="1" step="0.01" name="amount" required>
                </div>
                <div class="col-md-3 form-group">
                    <label>تاریخ پرداخت</label>
                    <input class="form-control" type="date" name="paid_at" value="<?=e(date('Y-m-d'))?>" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>روش پرداخت</label>
                    <select class="form-control" name="method">
                        <option value="cash">نقدی</option>
                        <option value="card">کارت</option>
                        <option value="transfer">واریز بانکی</option>
                        <option value="cheque">چک</option>
                        <option value="other">سایر</option>
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>شماره پیگیری</label>
                    <input class="form-control" name="reference_no" maxlength="100">
                </div>
                <div class="col-md-4 form-group">
                    <label>توضیحات</label>
                    <input class="form-control" name="notes" maxlength="500">
                </div>
            </div>
            <button class="btn btn-primary" type="submit">ثبت پرداخت</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">سوابق پرداخت</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ساختمان</th><th>واحد</th><th>دوره</th><th>شارژ</th>
                        <th>مبلغ</th><th>تاریخ</th><th>روش</th><th>پیگیری</th><th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($payments as $p): ?>
                    <tr>
                        <td><?=e($p['building_name'])?></td>
                        <td><?=e($p['unit_no'])?></td>
                        <td><?=e($p['period'])?></td>
                        <td><?=e($p['title'])?></td>
                        <td><?=money($p['amount'])?></td>
                        <td><?=e($p['paid_at'])?></td>
                        <td><?=e($p['method'])?></td>
                        <td><?=e($p['reference_no'] ?? '-')?></td>
                        <td>
                            <form method="post" style="display:inline" onsubmit="return confirm('این پرداخت حذف شود؟')">
                                <?=csrf_field()?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?=$p['id']?>">
                                <button class="btn btn-sm btn-outline-danger" type="submit">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php page_footer(); ?>
