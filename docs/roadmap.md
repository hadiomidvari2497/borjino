# نقشه راه توسعه برجینو

> این سند مرجع وضعیت پروژه برای همه توسعه‌دهندگان است. هر فاز تا زمانی که معیارهای تکمیل آن انجام نشده، `Done` محسوب نمی‌شود.

## وضعیت فازها

| فاز | وضعیت | خروجی اصلی |
|---|---|---|
| 0. تحلیل و baseline | Done | نیازمندی‌ها، معماری اولیه و تصمیم‌های پایه |
| 1. دیتابیس | Done (baseline) | PostgreSQL schema + PK/FK + constraints |
| 2. قالب خام UI | Done (baseline) | پوسته RTL بر مبنای Nextable |
| 3. زیرساخت Backend | Todo | پروژه، config، migrations، logging، error handling |
| 4. احراز هویت و RBAC | Todo | admin، administrators، کاربران، گروه‌ها و permissions |
| 5. ساختمان/بلوک/واحد | Todo | CRUD + ساخت گروهی واحدها + قوانین حذف |
| 6. اشخاص/پرسنل/اعضا | Todo | اشخاص حقیقی/حقوقی، نقش‌ها، مالک/مستأجر |
| 7. اجاره و فروش | Todo | قراردادها و وضعیت واحد |
| 8. موتور شارژ | Todo | ۱۰ روش محاسبه + cost-based allocation |
| 9. قبوض/تعمیرات/پرداخت | Todo | هزینه‌های متغیر، charge items و پرداخت |
| 10. پیامک/اعلان/تنظیمات | Todo | هشدار نقص داده، صدور شارژ و SMS |
| 11. گزارش‌ها | Todo | ۵ گروه گزارش ذکرشده در سند |
| 12. تست و سخت‌سازی | Todo | تست واحد/یکپارچه، امنیت، validation و edge cases |
| 13. انتشار | Todo | Docker/CI/CD، migration، backup و deployment |

## قرارداد توسعه برای تیم

1. هر تغییر دامنه‌ای ابتدا باید در `docs/` مستند شود.
2. هر feature باید migration و تست متناظر داشته باشد.
3. FKها و محدودیت‌های دیتابیس نباید برای راحتی API حذف یا دور زده شوند.
4. حذف موجودیت‌های والد با وابستگی فعال باید توسط DB و application layer کنترل شود.
5. اطلاعات مالی باید با `NUMERIC` نگهداری شوند؛ از float برای مبالغ استفاده نشود.
6. رمز عبور فقط به‌صورت hash امن (ترجیحاً Argon2id) ذخیره شود.
7. کاربر `admin` و گروه `administrators` سیستم هستند و طبق نیازمندی غیرقابل حذف/تغییرند.
8. هر PR باید مشخص کند کدام آیتم‌های این roadmap را تغییر می‌دهد.

## جزئیات فاز 1 — دیتابیس

### موجودیت‌های اصلی
- `buildings` → `blocks` → `units`
- `units` → `unit_parkings`, `unit_storages`
- `persons` → `unit_memberships`
- `buildings` → `building_personnel` → `personnel_roles`
- `units` → `contracts`
- `users` → `user_groups` → `permission_groups` → `group_permissions` → `permissions`
- `buildings` → `charge_settings`, `charge_cost_types`, `common_bills`, `repairs`, `charge_periods`
- `charge_periods` → `charges` → `charge_items`, `payments`
- `users/persons` → `notifications`; `users` → `audit_logs`

### قوانین مهم رابطه‌ای
- ساختمان تا وقتی بلوک دارد حذف نمی‌شود (`RESTRICT`).
- بلوک تا وقتی واحد دارد حذف نمی‌شود (`RESTRICT`).
- واحد تا وقتی وابستگی‌های مالک/مستأجر/قرارداد و سایر داده‌های وابسته دارد حذف نمی‌شود.
- شماره بلوک در هر ساختمان یکتا است.
- شماره واحد در هر بلوک یکتا است.
- پارکینگ/انبار در محدوده هر واحد شماره یکتا دارند.
- دوره شارژ برای هر ساختمان و ماه/سال یکتا است.
- برای هر دوره، هر واحد یک رکورد شارژ دارد.

## فاز 2 — UI baseline

قالب `Nextable` ارسالی شامل پوسته RTL و دو نسخه Light/Dark است. در baseline، ساختار template جدا از منطق کسب‌وکار نگه داشته می‌شود تا در زمان انتخاب stack نهایی، وابستگی به قالب مانع معماری نشود.

## تعریف Done

هر فاز وقتی Done است که:
- مستندات به‌روز شده باشند؛
- کد قابل اجرا باشد؛
- validation و خطاهای اصلی پوشش داده شده باشند؛
- تست‌های مرتبط سبز باشند؛
- تغییرات در Git commit شده باشند؛
- roadmap و README با وضعیت واقعی هماهنگ باشند.

## Next Up

اولین کار بعد از این baseline: انتخاب/تثبیت stack اجرایی Backend و Frontend، سپس ساخت migrations واقعی از `schema.sql` و راه‌اندازی محیط توسعه محلی/CI.
