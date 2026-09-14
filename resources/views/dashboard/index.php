<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>داشبورد | برجینو</title>
</head>
<body>
    <main>
        <h1>داشبورد برجینو</h1>
        <p>کاربر واردشده: <?= htmlspecialchars((string) $username, ENT_QUOTES, 'UTF-8') ?></p>
        <form method="post" action="/logout">
            <input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit">خروج</button>
        </form>
    </main>
</body>
</html>
