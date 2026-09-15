<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $person ? 'ویرایش شخص' : 'افزودن شخص' ?> | برجینو</title></head>
<body><main>
<h1><?= $person ? 'ویرایش شخص' : 'افزودن شخص' ?></h1>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>">
<label>نوع شخص
<select name="person_type" id="person_type" onchange="togglePersonType()">
<option value="individual" <?= (($person['person_type'] ?? 'individual') === 'individual') ? 'selected' : '' ?>>حقیقی</option>
<option value="legal" <?= (($person['person_type'] ?? '') === 'legal') ? 'selected' : '' ?>>حقوقی</option>
</select></label>
<fieldset id="individual-fields"><legend>اطلاعات شخص حقیقی</legend>
<label>نام <input name="first_name" maxlength="100" value="<?= htmlspecialchars((string) ($person['first_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<label>نام خانوادگی <input name="last_name" maxlength="100" value="<?= htmlspecialchars((string) ($person['last_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
</fieldset>
<fieldset id="legal-fields"><legend>اطلاعات شخص حقوقی</legend>
<label>نام شخص حقوقی <input name="legal_name" maxlength="200" value="<?= htmlspecialchars((string) ($person['legal_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
</fieldset>
<label>کد ملی / شناسه ملی <input name="national_id" maxlength="30" value="<?= htmlspecialchars((string) ($person['national_id'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<label>تلفن <input name="phone" maxlength="30" value="<?= htmlspecialchars((string) ($person['phone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<label>تاریخ تولد / تأسیس <input type="date" name="birth_or_establishment_date" value="<?= htmlspecialchars((string) ($person['birth_or_establishment_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<label>آدرس <textarea name="address" rows="3"><?= htmlspecialchars((string) ($person['address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
<label>آدرس دوم <textarea name="secondary_address" rows="3"><?= htmlspecialchars((string) ($person['secondary_address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
<button type="submit">ذخیره</button> <a href="/persons">انصراف</a>
</form>
</main>
<script>
function togglePersonType() {
 const legal = document.getElementById('person_type').value === 'legal';
 document.getElementById('individual-fields').hidden = legal;
 document.getElementById('legal-fields').hidden = !legal;
}
togglePersonType();
</script>
</body></html>
