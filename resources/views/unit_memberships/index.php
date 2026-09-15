<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>اشخاص واحد | برجینو</title></head>
<body><main>
<h1>اشخاص واحد <?= htmlspecialchars((string) $unit['unit_number'], ENT_QUOTES, 'UTF-8') ?></h1>
<p><?= htmlspecialchars((string) $unit['building_name'], ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars((string) ($unit['block_name'] ?: 'بلوک'), ENT_QUOTES, 'UTF-8') ?></p>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<p><a href="/unit-memberships/create?unit_id=<?= (int) $unit['id'] ?>">+ افزودن مالک/مستأجر</a> | <a href="/units">بازگشت به واحدها</a></p>
<table><thead><tr><th>نوع</th><th>شخص</th><th>کد ملی/شناسه</th><th>تلفن</th><th>شروع</th><th>پایان</th><th>جاری</th><th>عملیات</th></tr></thead><tbody>
<?php foreach ($memberships as $row): ?>
<tr>
<td><?= $row['membership_type'] === 'owner' ? 'مالک' : 'مستأجر' ?></td>
<td><?= htmlspecialchars($row['person_type'] === 'legal' ? (string) $row['legal_name'] : trim((string) $row['first_name'] . ' ' . (string) $row['last_name']), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string) ($row['national_id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string) ($row['phone'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string) ($row['start_date'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string) ($row['end_date'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= (int) $row['is_current'] === 1 ? 'بله' : 'خیر' ?></td>
<td><a href="/unit-memberships/edit?id=<?= (int) $row['id'] ?>">ویرایش</a>
<form method="post" action="/unit-memberships/delete" style="display:inline" onsubmit="return confirm('آیا از حذف این ارتباط اطمینان دارید؟');"><input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button type="submit">حذف</button></form></td>
</tr>
<?php endforeach; ?>
<?php if ($memberships === []): ?><tr><td colspan="8">برای این واحد مالک یا مستأجری ثبت نشده است.</td></tr><?php endif; ?>
</tbody></table>
</main></body></html>
