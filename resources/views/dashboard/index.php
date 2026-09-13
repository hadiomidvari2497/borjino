<!doctype html>
<html lang="fa" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>داشبورد | برجینو</title>
  <style>
    :root { --bg:#f5f7fb; --surface:#fff; --text:#172033; --muted:#7b8498; --primary:#5867dd; --border:#e7eaf1; }
    *{box-sizing:border-box} body{margin:0;background:var(--bg);color:var(--text);font-family:Tahoma,Arial,sans-serif}
    .layout{display:grid;grid-template-columns:260px 1fr;min-height:100vh}
    .sidebar{background:var(--surface);border-left:1px solid var(--border);padding:24px;position:sticky;top:0;height:100vh}
    .brand{font-size:24px;font-weight:700;color:var(--primary);margin-bottom:36px}
    .nav a{display:block;padding:12px;border-radius:10px;color:var(--text);text-decoration:none;margin:5px 0}
    .nav a.active,.nav a:hover{background:#eef0ff;color:var(--primary)}
    .main{padding:24px 30px}
    .topbar{display:flex;justify-content:space-between;align-items:center;background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:16px 20px;margin-bottom:24px}
    .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
    .card{background:var(--surface);border:1px solid var(--border);border-radius:14px;padding:20px}
    .metric{font-size:28px;font-weight:700;margin-top:10px}
    .muted{color:var(--muted);font-size:13px}
    .section{margin-top:20px}
    .section h2{font-size:18px}
    .table{width:100%;border-collapse:collapse}
    .table th,.table td{padding:13px;text-align:right;border-bottom:1px solid var(--border)}
    .badge{display:inline-block;padding:5px 9px;border-radius:20px;background:#eef0ff;color:var(--primary);font-size:12px}
    .btn{padding:8px 16px;background:var(--primary);color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-block}
    .btn:hover{background:#4a58c7}
    .user-info{display:flex;align-items:center;gap:12px}
    .user-avatar{width:36px;height:36px;border-radius:50%;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700}
    @media(max-width:900px){.layout{grid-template-columns:1fr}.sidebar{display:none}.grid{grid-template-columns:repeat(2,1fr)}}
  </style>
</head>
<body>
<div class="layout">
  <aside class="sidebar">
    <div class="brand">برجینو</div>
    <nav class="nav">
      <a class="active" href="/dashboard">داشبورد</a>
      <a href="#">ساختمان‌ها</a>
      <a href="#">بلوک‌ها</a>
      <a href="#">واحدها</a>
      <a href="#">اعضای ساختمان</a>
      <a href="#">پرسنل</a>
      <a href="#">قراردادها</a>
      <a href="#">شارژ و پرداخت‌ها</a>
      <a href="#">گزارش‌ها</a>
      <a href="#">کاربران و دسترسی</a>
      <a href="#">تنظیمات</a>
    </nav>
  </aside>
  <main class="main">
    <header class="topbar">
      <strong>داشبورد مدیریت ساختمان</strong>
      <div class="user-info">
        <div class="user-avatar">A</div>
        <div>
          <div style="font-weight:600"><?= htmlspecialchars($user['username'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></div>
          <a href="/logout" class="btn" style="font-size:12px;padding:6px 12px">خروج</a>
        </div>
      </div>
    </header>
    <section class="grid">
      <div class="card"><div class="muted">تعداد ساختمان‌ها</div><div class="metric">0</div></div>
      <div class="card"><div class="muted">تعداد واحدها</div><div class="metric">0</div></div>
      <div class="card"><div class="muted">شارژ صادرشده</div><div class="metric">۰ ریال</div></div>
      <div class="card"><div class="muted">بدهی واحدها</div><div class="metric">۰ ریال</div></div>
    </section>
    <section class="card section">
      <h2>آخرین فعالیت‌ها</h2>
      <table class="table">
        <thead>
          <tr><th>کاربر</th><th>عملیات</th><th>زمان</th><th>وضعیت</th></tr>
        </thead>
        <tbody>
          <tr><td colspan="4" class="muted">هنوز فعالیتی ثبت نشده است.</td></tr>
        </tbody>
      </table>
    </section>
  </main>
</div>
</body>
</html>