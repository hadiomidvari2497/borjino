<?php
/** @var array $personnel */
/** @var array $buildings */
/** @var array $roles */
/** @var int|null $building_id */
/** @var int|null $role_id */
/** @var string $search */
/** @var string $csrf_token */
/** @var string|null $error */
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>پرسنل ساختمان | برجینو</title></head>
<body>
<main>
<h1>پرسنل ساختمان</h1>
<p><a href="/dashboard">داشبورد</a> | <a href="/persons">اشخاص</a> | <a href="/building-personnel/create">افزودن پرسنل</a></p>
<?php if ($error !== null): ?><p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="get" action="/building-personnel">
<label>ساختمان
<select name="building_id"><option value="">همه ساختمان‌ها</option><?php foreach ($buildings as $building): ?><option value="<?= (int) $building['id'] ?>" <?= $building_id === (int)$building['id'] ? 'selected' : '' ?>><?= htmlspecialchars($building['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label>
<label>نقش
<select name="role_id"><option value="">همه نقش‌ها</option><?php foreach ($roles as $role): ?><option value="<?= (int) $role['id'] ?>" <?= $role_id === (int)$role['id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['title'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label>
<label>جستجو <input type="search" name="q" value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>" placeholder="نام، کد ملی یا تلفن"></label>
<button type="submit">فیلتر</button>
</form>
<table border="1" cellpadding="8"><thead><tr><th>ساختمان</th><th>شخص</th><th>نقش</th><th>تلفن</th><th>عملیات</th></tr></thead><tbody>
<?php foreach ($personnel as $item): $name = $item['person_type'] === 'legal' ? $item['legal_name'] : trim($item['first_name'].' '.$item['last_name']); ?><tr>
<td><?= htmlspecialchars($item['building_name'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars($item['role_title'], ENT_QUOTES, 'UTF-8') ?></td>
<td><?= htmlspecialchars((string)($item['phone'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
<td><a href="/building-personnel/edit?id=<?= (int)$item['id'] ?>">ویرایش</a>
<form method="post" action="/building-personnel/delete" style="display:inline" onsubmit="return confirm('این رکورد حذف شود؟')"><input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><button type="submit">حذف</button></form></td>
</tr><?php endforeach; ?>
<?php if ($personnel === []): ?><tr><td colspan="5">رکوردی پیدا نشد.</td></tr><?php endif; ?>
</tbody></table>
</main>
</body></html>
