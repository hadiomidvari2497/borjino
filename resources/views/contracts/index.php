<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>قراردادها | برجینو</title></head>
<body><main>
<h1>قراردادهای اجاره و فروش</h1>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<p><a href="/contracts/create<?= $unit_id ? '?unit_id='.(int)$unit_id : '' ?>">+ ثبت قرارداد</a> | <a href="/units">واحدها</a></p>
<form method="get">
<input name="q" placeholder="ساختمان، واحد، شخص، کد ملی..." value="<?= htmlspecialchars((string)$search, ENT_QUOTES, 'UTF-8') ?>">
<select name="type"><option value="">همه انواع</option><option value="rental" <?= $type === 'rental' ? 'selected' : '' ?>>اجاره</option><option value="sale" <?= $type === 'sale' ? 'selected' : '' ?>>فروش</option></select>
<button type="submit">فیلتر</button> <a href="/contracts">پاک کردن</a>
</form>
<table><thead><tr><th>ساختمان</th><th>بلوک/واحد</th><th>نوع</th><th>طرف قرارداد</th><th>مبلغ</th><th>تاریخ قرارداد</th><th>پایان</th><th>عملیات</th></tr></thead><tbody>
<?php foreach ($contracts as $row): ?>
<tr>
<td><?= htmlspecialchars($row['building_name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string)($row['block_name'] ?: 'بلوک '.$row['block_number']), ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars($row['unit_number'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= $row['contract_type'] === 'rental' ? 'اجاره' : 'فروش' ?></td>
<td><?= htmlspecialchars($row['person_type'] === 'legal' ? (string)$row['legal_name'] : trim((string)$row['first_name'].' '.(string)$row['last_name']), ENT_QUOTES, 'UTF-8') ?></td>
<td><?php if ($row['contract_type'] === 'rental'): ?>ودیعه: <?= htmlspecialchars((string)($row['deposit_amount'] ?? '-'), ENT_QUOTES, 'UTF-8') ?> / اجاره: <?= htmlspecialchars((string)($row['monthly_rent'] ?? '-'), ENT_QUOTES, 'UTF-8') ?><?php else: ?><?= htmlspecialchars((string)($row['sale_amount'] ?? '-'), ENT_QUOTES, 'UTF-8') ?><?php endif; ?></td>
<td><?= htmlspecialchars((string)$row['contract_date'], ENT_QUOTES, 'UTF-8') ?></td><td><?= htmlspecialchars((string)($row['end_date'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><a href="/contracts/edit?id=<?= (int)$row['id'] ?>">ویرایش</a>
<form method="post" action="/contracts/delete" style="display:inline" onsubmit="return confirm('آیا از حذف این قرارداد اطمینان دارید؟');"><input type="hidden" name="_token" value="<?= htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button type="submit">حذف</button></form></td>
</tr>
<?php endforeach; ?>
<?php if ($contracts === []): ?><tr><td colspan="8">قراردادی ثبت نشده است.</td></tr><?php endif; ?></tbody></table>
</main></body></html>
