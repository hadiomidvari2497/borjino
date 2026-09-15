<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $membership ? 'ویرایش ارتباط' : 'افزودن مالک/مستأجر' ?> | برجینو</title></head>
<body><main>
<h1><?= $membership ? 'ویرایش ارتباط' : 'افزودن مالک/مستأجر' ?></h1>
<p>واحد <?= htmlspecialchars((string) $unit['unit_number'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars((string) $unit['building_name'], ENT_QUOTES, 'UTF-8') ?></p>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>">
<label>شخص
<select name="person_id" required><option value="">انتخاب شخص</option>
<?php foreach ($persons as $person): ?>
<?php $label = $person['person_type'] === 'legal' ? (string) $person['legal_name'] : trim((string) $person['first_name'] . ' ' . (string) $person['last_name']); ?>
<option value="<?= (int) $person['id'] ?>" <?= ((int) ($membership['person_id'] ?? 0) === (int) $person['id']) ? 'selected' : '' ?>><?= htmlspecialchars($label . ($person['national_id'] ? ' — ' . $person['national_id'] : ''), ENT_QUOTES, 'UTF-8') ?></option>
<?php endforeach; ?></select></label>
<label>نوع ارتباط
<select name="membership_type" required><option value="owner" <?= (($membership['membership_type'] ?? '') === 'owner') ? 'selected' : '' ?>>مالک</option><option value="tenant" <?= (($membership['membership_type'] ?? '') === 'tenant') ? 'selected' : '' ?>>مستأجر</option></select></label>
<label>تاریخ شروع <input type="date" name="start_date" value="<?= htmlspecialchars((string) ($membership['start_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<label>تاریخ پایان <input type="date" name="end_date" value="<?= htmlspecialchars((string) ($membership['end_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label>
<label><input type="checkbox" name="is_current" value="1" <?= ((int) ($membership['is_current'] ?? 1) === 1) ? 'checked' : '' ?>> ارتباط جاری است</label>
<label>توضیحات <textarea name="notes" rows="4" maxlength="2000"><?= htmlspecialchars((string) ($membership['notes'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label>
<button type="submit">ذخیره</button> <a href="/unit-memberships?unit_id=<?= (int) $unit['id'] ?>">انصراف</a>
</form>
</main></body></html>
