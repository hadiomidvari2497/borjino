<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>بلوک‌ها | برجینو</title></head>
<body>
<main>
    <h1>بلوک‌ها</h1>
    <?php if ($error): ?><p role="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <p><a href="/blocks/create">+ افزودن بلوک</a></p>
    <form method="get">
        <input name="q" placeholder="جستجوی نام یا شماره بلوک" value="<?= htmlspecialchars((string)$search, ENT_QUOTES, 'UTF-8') ?>">
        <select name="building_id">
            <option value="">همه ساختمان‌ها</option>
            <?php foreach ($buildings as $building): ?>
                <option value="<?= (int)$building['id'] ?>" <?= ((int)($building_id ?? 0) === (int)$building['id']) ? 'selected' : '' ?>><?= htmlspecialchars($building['name'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">فیلتر</button>
    </form>
    <table>
        <thead><tr><th>ساختمان</th><th>شماره</th><th>نام</th><th>طبقات</th><th>واحدها</th><th>عملیات</th></tr></thead>
        <tbody>
        <?php foreach ($blocks as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['building_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int)$row['block_number'] ?></td>
                <td><?= htmlspecialchars((string)($row['name'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int)$row['floor_count'] ?></td>
                <td><?= (int)$row['units_count'] ?></td>
                <td>
                    <a href="/blocks/edit?id=<?= (int)$row['id'] ?>">ویرایش</a>
                    <form method="post" action="/blocks/delete" style="display:inline" onsubmit="return confirm('آیا از حذف این بلوک اطمینان دارید؟');">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button type="submit">حذف</button>
                    </form>
                    <a href="/units?block_id=<?= (int)$row['id'] ?>">واحدها</a>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($blocks === []): ?><tr><td colspan="6">بلوک ثبت‌شده‌ای وجود ندارد.</td></tr><?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
