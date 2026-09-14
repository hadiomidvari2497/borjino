<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ساختمان‌ها | برجینو</title>
</head>
<body>
<main>
    <header>
        <h1>مدیریت ساختمان‌ها</h1>
        <a href="/buildings/create">ثبت ساختمان جدید</a>
    </header>

    <?php if ($error): ?>
        <p role="alert"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="get" action="/buildings">
        <label for="q">جستجو</label>
        <input id="q" name="q" value="<?= htmlspecialchars((string) $search, ENT_QUOTES, 'UTF-8') ?>" placeholder="نام، کدپستی یا شهر">
        <button type="submit">جستجو</button>
        <?php if ($search !== ''): ?><a href="/buildings">حذف فیلتر</a><?php endif; ?>
    </form>

    <table>
        <thead><tr><th>نام</th><th>شهر</th><th>نوع</th><th>پارکینگ</th><th>انبار</th><th>عملیات</th></tr></thead>
        <tbody>
        <?php foreach ($buildings as $building): ?>
            <tr>
                <td><?= htmlspecialchars((string) $building['name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($building['city'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string) ($building['building_type'] ?? '-'), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int) $building['parking_count'] ?></td>
                <td><?= (int) $building['storage_count'] ?></td>
                <td>
                    <a href="/buildings/edit?id=<?= (int) $building['id'] ?>">ویرایش</a>
                    <form method="post" action="/buildings/delete" style="display:inline" onsubmit="return confirm('از حذف ساختمان مطمئن هستید؟');">
                        <input type="hidden" name="id" value="<?= (int) $building['id'] ?>">
                        <input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit">حذف</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</main>
</body>
</html>
