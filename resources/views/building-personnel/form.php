<?php
/** @var array|null $item */
/** @var array $buildings */
/** @var array $persons */
/** @var array $roles */
/** @var string $action */
/** @var string $csrf_token */
/** @var string|null $error */
$currentBuilding = (int)($item['building_id'] ?? ($_POST['building_id'] ?? 0));
$currentPerson = (int)($item['person_id'] ?? ($_POST['person_id'] ?? 0));
$currentRole = (int)($item['role_id'] ?? ($_POST['role_id'] ?? 0));
?>
<!doctype html>
<html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= $item ? 'ویرایش پرسنل' : 'افزودن پرسنل' ?> | برجینو</title></head>
<body><main>
<h1><?= $item ? 'ویرایش پرسنل ساختمان' : 'افزودن پرسنل ساختمان' ?></h1>
<p><a href="/building-personnel">بازگشت</a> | <a href="/persons/create">ثبت شخص جدید</a></p>
<?php if ($error !== null): ?><p role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<?php if ($buildings === [] || $persons === []): ?><p>برای ثبت پرسنل ابتدا حداقل یک ساختمان و یک شخص ایجاد کنید.</p><?php endif; ?>
<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
<label>ساختمان <select name="building_id" required><option value="">انتخاب کنید</option><?php foreach ($buildings as $building): ?><option value="<?= (int)$building['id'] ?>" <?= $currentBuilding === (int)$building['id'] ? 'selected' : '' ?>><?= htmlspecialchars($building['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label><br>
<label>شخص <select name="person_id" required><option value="">انتخاب کنید</option><?php foreach ($persons as $person): $name = $person['person_type'] === 'legal' ? $person['legal_name'] : trim($person['first_name'].' '.$person['last_name']); ?><option value="<?= (int)$person['id'] ?>" <?= $currentPerson === (int)$person['id'] ? 'selected' : '' ?>><?= htmlspecialchars($name . ($person['national_id'] ? ' — '.$person['national_id'] : ''), ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label><br>
<label>نقش <select name="role_id" required><option value="">انتخاب کنید</option><?php foreach ($roles as $role): ?><option value="<?= (int)$role['id'] ?>" <?= $currentRole === (int)$role['id'] ? 'selected' : '' ?>><?= htmlspecialchars($role['title'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label><br>
<button type="submit">ذخیره</button>
</form>
</main></body></html>
