<!doctype html>
<html lang="fa" dir="rtl">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>واحدها | برجینو</title></head>
<body>
<main>
    <h1>واحدها</h1>
    <?php if ($error): ?><p role="alert"><?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <?php if ($success = \App\Support\Session::get('unit.success')): ?><p role="status"><?= htmlspecialchars((string)$success, ENT_QUOTES, 'UTF-8') ?></p><?php \App\Support\Session::forget('unit.success'); endif; ?>
    <p><a href="/units/create<?= $building_id ? '?building_id='.(int)$building_id : '' ?>">+ افزودن واحد</a> | <a href="/persons">مدیریت اشخاص</a></p>
    <form method="get">
        <input name="q" placeholder="جستجوی شماره، کدپستی یا بلوک" value="<?= htmlspecialchars((string)$search, ENT_QUOTES, 'UTF-8') ?>">
        <select name="building_id" onchange="this.form.submit()"><option value="">همه ساختمان‌ها</option>
            <?php foreach ($buildings as $building): ?><option value="<?= (int)$building['id'] ?>" <?= $building_id === (int)$building['id'] ? 'selected' : '' ?>><?= htmlspecialchars($building['name'], ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
        </select>
        <select name="block_id"><option value="">همه بلوک‌ها</option>
            <?php foreach ($blocks as $block): ?><option value="<?= (int)$block['id'] ?>" <?= $block_id === (int)$block['id'] ? 'selected' : '' ?>><?= htmlspecialchars((string)($block['name'] ?: 'بلوک '.$block['block_number']), ENT_QUOTES, 'UTF-8') ?></option><?php endforeach; ?>
        </select>
        <select name="status"><option value="">همه وضعیت‌ها</option><?php foreach (['sold'=>'فروخته شده','rented'=>'اجاره‌داده شده','vacant'=>'خالی','under_repair'=>'در تعمیر'] as $key=>$label): ?><option value="<?= $key ?>" <?= $status === $key ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select>
        <select name="financial_status"><option value="">همه وضعیت‌های مالی</option><?php foreach (['settled'=>'تسویه','debtor'=>'بدهکار','creditor'=>'بستانکار'] as $key=>$label): ?><option value="<?= $key ?>" <?= $financial_status === $key ? 'selected' : '' ?>><?= $label ?></option><?php endforeach; ?></select>
        <button type="submit">فیلتر</button> <a href="/units">پاک کردن</a>
    </form>
    <table>
        <thead><tr><th>ساختمان</th><th>بلوک</th><th>واحد</th><th>طبقه</th><th>متراژ</th><th>وضعیت</th><th>مالی</th><th>جهت</th><th>عملیات</th></tr></thead>
        <tbody>
        <?php foreach ($units as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['building_name'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars((string)($row['block_name'] ?: 'بلوک '.$row['block_number']), ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($row['unit_number'], ENT_QUOTES, 'UTF-8') ?></td><td><?= (int)$row['floor_number'] ?></td><td><?= htmlspecialchars((string)$row['area_sqm'], ENT_QUOTES, 'UTF-8') ?> م²</td>
                <td><?= ['sold'=>'فروخته شده','rented'=>'اجاره‌داده شده','vacant'=>'خالی','under_repair'=>'در تعمیر'][$row['status']] ?? $row['status'] ?></td>
                <td><?= ['settled'=>'تسویه','debtor'=>'بدهکار','creditor'=>'بستانکار'][$row['financial_status']] ?? $row['financial_status'] ?></td>
                <td><?= ['north'=>'شمالی','south'=>'جنوبی','east'=>'شرقی','west'=>'غربی'][$row['direction']] ?? '-' ?></td>
                <td><a href="/units/edit?id=<?= (int)$row['id'] ?>">ویرایش</a> |
                    <a href="/unit-memberships?unit_id=<?= (int)$row['id'] ?>">مالک/مستأجر</a>
                    <form method="post" action="/units/delete" style="display:inline" onsubmit="return confirm('آیا از حذف این واحد اطمینان دارید؟');"><input type="hidden" name="_token" value="<?= htmlspecialchars((string)$csrf_token, ENT_QUOTES, 'UTF-8') ?>"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>"><button type="submit">حذف</button></form></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($units === []): ?><tr><td colspan="9">واحدی ثبت نشده است.</td></tr><?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
