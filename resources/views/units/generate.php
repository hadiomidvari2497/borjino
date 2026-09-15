<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>تولید خودکار واحدها | برجینو</title></head>
<body><main>
<h1>تولید خودکار واحدها</h1>
<p>ساختمان: <strong><?= htmlspecialchars((string)$block['building_name'], ENT_QUOTES, 'UTF-8') ?></strong></p>
<p>بلوک: <strong><?= htmlspecialchars((string)($block['name'] ?: 'بلوک '.$block['block_number']), ENT_QUOTES, 'UTF-8') ?></strong> — <?= (int)$block['floor_count'] ?> طبقه</p>
<?php if ($error): ?><p role="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<form method="post" action="/units/generate">
<input type="hidden" name="_token" value="<?= htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="block_id" value="<?= (int)$block['id'] ?>">
<label>تعداد واحد در هر طبقه <input type="number" name="units_per_floor" min="1" max="50" value="2" required></label>
<p>شماره‌گذاری به‌صورت خودکار انجام می‌شود؛ مثلاً طبقه ۱: ۱۰۱، ۱۰۲ و طبقه ۲: ۲۰۱، ۲۰۲. واحدهای جدید با متراژ ۰ ایجاد می‌شوند تا بعداً اطلاعات واقعی آن‌ها تکمیل شود. واحدهای موجود دوباره ایجاد نمی‌شوند.</p>
<button type="submit">تولید واحدها</button> <a href="/blocks">انصراف</a>
</form></main></body></html>
