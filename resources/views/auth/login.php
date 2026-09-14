<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ورود | برجینو</title>
    <style>
        body { margin: 0; font-family: Tahoma, Arial, sans-serif; background: #f5f6fa; }
        .wrap { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
        .card { width: min(420px, 100%); background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 12px 40px rgba(0,0,0,.08); }
        h1 { margin-top: 0; }
        label { display: block; margin: 16px 0 8px; }
        input { width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #d9dce5; border-radius: 10px; }
        button { width: 100%; margin-top: 20px; padding: 12px; border: 0; border-radius: 10px; background: #4f46e5; color: #fff; cursor: pointer; }
        .error { background: #fee2e2; color: #991b1b; padding: 10px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="wrap">
    <main class="card">
        <h1>ورود به برجینو</h1>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars((string) $error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <form method="post" action="/login">
            <input type="hidden" name="_token" value="<?= htmlspecialchars((string) $csrf_token, ENT_QUOTES, 'UTF-8') ?>">
            <label for="username">نام کاربری</label>
            <input id="username" name="username" type="text" autocomplete="username" required>
            <label for="password">رمز عبور</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit">ورود</button>
        </form>
    </main>
</div>
</body>
</html>
