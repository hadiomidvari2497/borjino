<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ورود | برجینو</title>
  <style>
    :root { --bg:#f5f7fb; --surface:#fff; --text:#172033; --muted:#7b8498; --primary:#5867dd; --primary-hover:#4a58c7; --border:#e7eaf1; --error:#dc3545; --success:#28a745; }
    *{box-sizing:border-box} body{margin:0;background:var(--bg);color:var(--text);font-family:Tahoma,Arial,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
    .login-container{width:100%;max-width:420px}
    .card{background:var(--surface);border:1px solid var(--border);border-radius:16px;padding:40px;box-shadow:0 4px 24px rgba(23,32,51,0.08)}
    .brand{font-size:28px;font-weight:700;color:var(--primary);text-align:center;margin-bottom:8px}
    .subtitle{text-align:center;color:var(--muted);margin-bottom:32px;font-size:14px}
    .form-group{margin-bottom:20px}
    label{display:block;margin-bottom:8px;font-weight:500;font-size:14px}
    input[type="text"], input[type="password"]{width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:10px;font-size:14px;transition:border-color 0.2s,box-shadow 0.2s}
    input:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(88,103,221,0.15)}
    .btn{width:100%;padding:13px;background:var(--primary);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;transition:background 0.2s}
    .btn:hover{background:var(--primary-hover)}
    .btn:disabled{opacity:0.6;cursor:not-allowed}
    .alert{padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:13px;display:none}
    .alert-error{background:#fef2f2;color:var(--error);border:1px solid #fecaca;display:block}
    .alert-success{background:#f0fdf4;color:var(--success);border:1px solid #bbf7d0;display:block}
    .footer{text-align:center;margin-top:24px;color:var(--muted);font-size:13px}
    .footer a{color:var(--primary);text-decoration:none}
    .footer a:hover{text-decoration:underline}
    .toggle-password{position:absolute;left:14px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--muted);cursor:pointer;font-size:13px;padding:4px}
    .toggle-password:hover{color:var(--primary)}
    .input-wrapper{position:relative}
    .input-wrapper input{padding-left:44px}
  </style>
</head>
<body>
  <div class="login-container">
    <div class="card">
      <div class="brand">برجینو</div>
      <p class="subtitle">سامانه مدیریت ساختمان<br>برای ورود نام کاربری و رمز عبور خود را وارد کنید</p>

      <?php if (!empty($error)): ?>
        <div class="alert alert-error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="alert"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="POST" action="/login" novalidate>
        <div class="form-group">
          <label for="username">نام کاربری</label>
          <div class="input-wrapper">
            <input type="text" id="username" name="username" autocomplete="username" required autofocus value="<?= htmlspecialchars($old['username'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="password">رمز عبور</label>
          <div class="input-wrapper">
            <input type="password" id="password" name="password" autocomplete="current-password" required>
            <button type="button" class="toggle-password" aria-label="نمایش/مخفی رمز عبور" onclick="togglePassword()">نمایش</button>
          </div>
        </div>

        <button type="submit" class="btn" id="submitBtn">ورود به سیستم</button>
      </form>

      <div class="footer">
        <a href="/">بازگشت به خانه</a>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const btn = document.querySelector('.toggle-password');
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = 'مخفی';
      } else {
        input.type = 'password';
        btn.textContent = 'نمایش';
      }
    }
  </script>
</body>
</html>