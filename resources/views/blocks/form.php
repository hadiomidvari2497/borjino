<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $block ? 'ویرایش بلوک' : 'ثبت بلوک' ?> | برجینو</title></head>
<body>
<main>
    <h1><?= $block ? 'ویرایش بلوک' : 'ثبت بلوک جدید' ?></h1>
    <?php if ($error): ?><p role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <label>ساختمان
            <select name="building_id" required>
                <option value="">انتخاب کنید</option>
                <?php foreach ($buildings as $item): ?>
                    <option value="<?= (int) $item['id'] ?>" <?= ((int)($block['building_id'] ?? 0) === (int)$item['id']) ? 'selected' : '' ?>><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <label>شماره بلوک <input type="number" name="block_number" min="1" required value="<?= (int)($block['block_number'] ?? 1) ?>"></label><br>
        <label>نام بلوک <input maxlength="150" name="name" value="<?= htmlspecialchars((string)($block['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"></label><br>
        <label>تعداد طبقات <input type="number" name="floor_count" min="1" required value="<?= (int)($block['floor_count'] ?? 1) ?>"></label><br>
        <button type="submit">ذخیره</button>
        <a href="/blocks">انصراف</a>
    </form>
</main>
</body>
</html>
