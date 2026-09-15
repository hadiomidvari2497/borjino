<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>اشخاص | برجینو</title></head>
<body><main>
<h1>اشخاص</h1>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<p><a href="/persons/create">+ افزودن شخص</a></p>
<form method="get">
<input name="q" placeholder="نام، کد ملی، تلفن..." value="<?= htmlspecialchars((string) $search, ENT_QUOTES, 'UTF-8') ?>">
<select name="type"><option value="">همه انواع</option><option value="individual" <?= $type === 'individual' ? 'selected' : '' ?>>حقیقی</option><option value="legal" <?= $type === 'legal' ? 'selected' : '' ?>>حقوقی</option></select>
<button type="submit">فیلتر</button>
</form>
<table><thead><tr><th>نوع</th><th>نام</th><th>کد ملی/شناسه</th><th>تلفن</th><th>عملیات</th></tr></thead><tbody>
<?php foreach ($persons as $person): ?>
<tr>
<td><?= $person['person_type'] === 'legal' ? 'حقوقی' : 'حقیقی' ?></td>
<td><?= htmlspecialchars($person['person_type'] === 'legal' ? (string) $person['legal_name'] : trim((string) $person['first_name'] . ' ' . (string) $person['last_name']), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string) ($person['national_id'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string) ($person['phone'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><a href="/persons/edit?id=<?= (int) $person['id'] ?>">ویرایش</a>
<form method="post" action="/persons/delete" style="display:inline" onsubmit="return confirm('آیا از حذف این شخص اطمینان دارید؟');"><input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int) $person['id'] ?>"><button type="submit">حذف</button></form></td>
</tr>
<?php endforeach; ?>
<?php if ($persons === []): ?><tr><td colspan="5">شخصی ثبت نشده است.</td></tr><?php endif; ?>
</tbody></table>
</main></body></html>
