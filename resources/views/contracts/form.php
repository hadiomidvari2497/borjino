<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $contract ? 'ویرایش قرارداد' : 'ثبت قرارداد' ?> | برجینو</title></head>
<body><main>
<h1><?= $contract ? 'ویرایش قرارداد' : 'ثبت قرارداد جدید' ?></h1>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
<input type="hidden" name="_token" value="<?= htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8') ?>">
<label>واحد <select name="unit_id" required><option value="">انتخاب کنید</option><?php foreach ($units as $u): ?><option value="<?= (int)$u['id'] ?>" <?= ((int)($contract['unit_id'] ?? $unit['id'] ?? 0) === (int)$u['id']) ? 'selected' : '' ?>><?= htmlspecialchars($u['building_name'].' / '.($u['block_name'] ?: 'بلوک '.$u['block_number']).' / واحد '.$u['unit_number'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?></select></label><br>
<label>نوع قرارداد <select name="contract_type" required><option value="rental" <?= ($contract['contract_type'] ?? 'rental') === 'rental' ? 'selected' : '' ?>>اجاره</option><option value="sale" <?= ($contract['contract_type'] ?? '') === 'sale' ? 'selected' : '' ?>>فروش</option></select></label><br>
<label>طرف قرارداد <select name="party_person_id" required><option value="">انتخاب کنید</option><?php foreach ($persons as $p): $name = $p['person_type'] === 'legal' ? $p['legal_name'] : trim($p['first_name'].' '.$p['last_name']); ?><option value="<?= (int)$p['id'] ?>" <?= ((int)($contract['party_person_id'] ?? 0) === (int)$p['id']) ? 'selected' : '' ?>><?= htmlspecialchars((string)$name, ENT_QUOTES, 'UTF-8') ?><?= $p['national_id'] ? ' - '.htmlspecialchars($p['national_id'], ENT_QUOTES, 'UTF-8') : '' ?></option><?php endforeach; ?></select></label><br>
<label>مبلغ ودیعه <input type="number" min="0" step="0.01" name="deposit_amount" value="<?= htmlspecialchars((string)($contract['deposit_amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<label>اجاره ماهانه <input type="number" min="0" step="0.01" name="monthly_rent" value="<?= htmlspecialchars((string)($contract['monthly_rent'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<label>مبلغ فروش <input type="number" min="0" step="0.01" name="sale_amount" value="<?= htmlspecialchars((string)($contract['sale_amount'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<label>تاریخ قرارداد <input type="date" name="contract_date" required value="<?= htmlspecialchars((string)($contract['contract_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<label>تاریخ پایان <input type="date" name="end_date" value="<?= htmlspecialchars((string)($contract['end_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
<button type="submit">ذخیره</button> <a href="/contracts">انصراف</a>
</form></main></body></html>
