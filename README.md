# برجینو — نسخه ساده PHP + MySQL

سیستم مدیریت ساختمان با PHP خام و MySQL، مناسب XAMPP.

## نصب
1. پروژه را داخل `htdocs/borjino` قرار دهید.
2. در phpMyAdmin دیتابیس `borjino` بسازید.
3. فایل `database.sql` را Import کنید.
4. `setup_admin.php` را باز کنید و کاربر مدیر را بسازید.
5. وارد `login.php` شوید.

بدون Composer، بدون Framework و بدون Migration.


## CI/CD

GitHub Actions workflow در `.github/workflows/ci.yml` با هر Push به `main` و هر Pull Request اجرا می‌شود و:
- syntax تمام فایل‌های PHP خارج از `docs/` را بررسی می‌کند.
- ساختار اصلی پروژه را کنترل می‌کند.
- حداقل ساختار SQL را اعتبارسنجی می‌کند.
- یک Artifact قابل تحویل از پروژه می‌سازد که پوشه `docs/` را شامل نمی‌شود.
