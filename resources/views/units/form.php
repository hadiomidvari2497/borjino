<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $unit ? 'ویرایش واحد' : 'افزودن واحد' ?> | برجینو</title></head>
<body><main>
<h1><?= $unit ? 'ویرایش واحد' : 'افزودن واحد' ?></h1>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" name="_token" value="<?= htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8') ?>">
<label>ساختمان <select name="building_id" required><option value="">انتخاب کنید</option><?php foreach ($buildings as $b): ?><option value="<?= (int)$b['id'] ?>" <?= ((int)($unit['building_id'] ?? 0) === (int)$b['id']) ? 'selected' : '' ?>><?= htmlspecialchars($b['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label><br>
<label>بلوک <select name="block_id" required><option value="">انتخاب کنید</option><?php foreach ($blocks as $b): ?><option value="<?= (int)$b['id'] ?>" <?= ((int)($unit['block_id'] ?? 0) === (int)$b['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string)($b['name'] ?: 'بلوک '.$b['block_number']), ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label><br>
<label>شماره واحد <input name="unit_number" maxlength="50" required value="<?= htmlspecialchars((string)($unit['unit_number'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<label>کد پستی <input name="postal_code" maxlength="20" value="<?= htmlspecialchars((string)($unit['postal_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<label>طبقه <input type="number" name="floor_number" min="1" required value="<?= (int)($unit['floor_number'] ?? 1) ?>"></label><br>
<label>متراژ (مترمربع) <input type="number" step="0.01" min="0" name="area_sqm" required value="<?= htmlspecialchars((string)($unit['area_sqm'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<label>وضعیت <select name="status"><?php foreach (['sold'=>'فروخته شده','rented'=>'اجاره‌داده شده','vacant'=>'خالی','under_repair'=>'در تعمیر'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($unit['status'] ?? 'vacant') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></label><br>
<label>وضعیت مالی <select name="financial_status"><?php foreach (['settled'=>'تسویه','debtor'=>'بدهکار','creditor'=>'بستانکار'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($unit['financial_status'] ?? 'settled') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></label><br>
<label>جهت <select name="direction"><option value="">انتخاب نشده</option><?php foreach (['north'=>'شمالی','south'=>'جنوبی','east'=>'شرقی','west'=>'غربی'] as $k=>$v): ?><option value="<?= $k ?>" <?= ($unit['direction'] ?? '') === $k ? 'selected' : '' ?>><?= $v ?></option><?php endforeach; ?></select></label><br>
<label>یادداشت<br><textarea name="notes" rows="5" cols="50"><?= htmlspecialchars((string)($unit['notes'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label><br>
<button type="submit">ذخیره</button> <a href="/units">انصراف</a>
</form></main></body></html>
