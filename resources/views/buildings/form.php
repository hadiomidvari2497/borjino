<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $building ? 'ویرایش ساختمان' : 'ثبت ساختمان' ?> | برجینو</title>
</head>
<body>
<main>
    <h1><?= $building ? 'ویرایش ساختمان' : 'ثبت ساختمان جدید' ?></h1>

    <?php if ($error): ?>
        <p role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>">

        <label>نام ساختمان <input required maxlength="150" name="name" value="<?= htmlspecialchars((string) ($building['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
        <label>کد پستی <input name="postal_code" value="<?= htmlspecialchars((string) ($building['postal_code'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
        <label>نوع ساختمان <input name="building_type" value="<?= htmlspecialchars((string) ($building['building_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
        <label>تاریخ ساخت <input type="date" name="construction_date" value="<?= htmlspecialchars((string) ($building['construction_date'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
        <label>تعداد پارکینگ <input type="number" min="0" name="parking_count" value="<?= (int) ($building['parking_count'] ?? 0) ?>"></label><br>
        <label>تعداد انبار <input type="number" min="0" name="storage_count" value="<?= (int) ($building['storage_count'] ?? 0) ?>"></label><br>
        <label>استان <input name="province" value="<?= htmlspecialchars((string) ($building['province'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
        <label>شهر <input name="city" value="<?= htmlspecialchars((string) ($building['city'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
        <label>آدرس <textarea name="address"><?= htmlspecialchars((string) ($building['address'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea></label><br>

        <button type="submit">ذخیره</button>
        <a href="/buildings">انصراف</a>
    </form>
</main>
</body>
</html>
