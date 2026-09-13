# Borjino Database

## تصمیم نهایی

- Language: raw PHP
- Database: MySQL 8.0+
- Storage engine: InnoDB
- Charset: utf8mb4
- Primary schema: `schema.mysql.sql`

`schema.sql` نسخه قدیمی PostgreSQL از baseline اولیه است و برای اجرای پروژه استفاده نمی‌شود.

## اصول

- PKها در MySQL به‌صورت `BIGINT UNSIGNED AUTO_INCREMENT` هستند.
- FKها برای تمام روابط دامنه‌ای تعریف شده‌اند.
- حذف والدها در روابط حساس `RESTRICT` است تا داده‌ها ناخواسته حذف نشوند.
- روابط many-to-many با جدول واسط و PK مرکب پیاده شده‌اند.
- مبالغ با `DECIMAL(18,2)` ذخیره می‌شوند و نباید در PHP با float محاسبه مالی شوند.
- داده متنی فارسی با `utf8mb4` ذخیره می‌شود.
- رمز عبور فقط با `password_hash()` در PHP ذخیره خواهد شد.

## Migration strategy

در مرحله بعد، این schema به migrationهای شماره‌گذاری‌شده تقسیم می‌شود تا توسعه‌دهندگان بتوانند تغییرات دیتابیس را نسخه‌بندی و rollback کنند.
