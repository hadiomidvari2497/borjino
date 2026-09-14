<?php
/**
 * Unit Create/Edit View
 */
include __DIR__ . '/../components/form.php';

$isEdit = isset($unit) && !empty($unit['id']);
$title = $isEdit ? 'ویرایش واحد' : 'افزودن واحد جدید';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => 'واحدها', 'href' => '/units'],
    ['label' => $isEdit ? 'ویرایش' : 'افزودن جدید', 'href' => '#']
];
$currentRoute = '/units';

$pageActions = '
<a href="/units" class="btn btn-secondary">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    بازگشت
</a>
';

$formAction = $isEdit ? '/units/'.$unit['id'] : '/units';
$formMethod = 'POST';

$old = $old ?? ($isEdit ? $unit : []);
$errors = $errors ?? [];

$buildings = [
    1 => 'ساختمان سپهر',
    2 => 'مجتمع تجاری پارسیان',
    3 => 'ساختمان اداری آفتاب',
];

$blocks = [
    1 => 'بلوک الف',
    2 => 'بلوک ب',
    3 => 'بلوک ج',
    4 => 'بلوک تجاری ۱',
    5 => 'بلوک تجاری ۲',
];

$statuses = [
    'sold' => 'فروخته شده',
    'rented' => 'اجاره‌داده شده',
    'vacant' => 'خالی',
    'under_repair' => 'در تعمیر',
];

$financialStatuses = [
    'settled' => 'تسویه',
    'debtor' => 'بدهکار',
    'creditor' => 'بستانکار',
];

$directions = [
    'north' => 'شمالی',
    'south' => 'جنوبی',
    'east' => 'شرقی',
    'west' => 'غربی',
];

$content = '
<div class="grid grid-2" style="gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">اطلاعات اصلی</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="'.$formAction.'" novalidate>
                '.($isEdit ? formHidden('_method', 'PUT') : '').'
                '.formField('ساختمان *', formSelect('building_id', $buildings, $old['building_id'] ?? '', ['required' => true], $errors), '', $errors['building_id'] ?? '', true).'
                '.formField('بلوک *', formSelect('block_id', $blocks, $old['block_id'] ?? '', ['required' => true], $errors), '', $errors['block_id'] ?? '', true).'
                '.formField('شماره واحد *', formInput('unit_number', 'text', $old['unit_number'] ?? '', ['required' => true, 'placeholder' => 'مثال: ۱۰۱، م-۱، ک-۲'], $errors), 'شماره واحد باید در هر بلوک یکتا باشد', $errors['unit_number'] ?? '', true).'
                '.formField('طبقه *', formInput('floor_number', 'number', $old['floor_number'] ?? '', ['required' => true, 'min' => 0, 'placeholder' => 'شماره طبقه'], $errors), 'طبقه همکف = ۰', $errors['floor_number'] ?? '', true).'
                '.formField('متراژ (متر مربع) *', formInput('area_sqm', 'number', $old['area_sqm'] ?? '', ['required' => true, 'step' => '0.01', 'min' => '0.01', 'placeholder' => 'مثال: ۱۲۰.۵'], $errors), '', $errors['area_sqm'] ?? '', true).'
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">وضعیت و ویژگی‌ها</h3>
        </div>
        <div class="card-body">
            '.formField('وضعیت واحد *', formSelect('status', $statuses, $old['status'] ?? 'vacant', ['required' => true], $errors), '', $errors['status'] ?? '', true).'
            '.formField('وضعیت مالی *', formSelect('financial_status', $financialStatuses, $old['financial_status'] ?? 'settled', ['required' => true], $errors), '', $errors['financial_status'] ?? '', true).'
            '.formField('جهت', formSelect('direction', $directions, $old['direction'] ?? '', [], $errors), '', $errors['direction'] ?? '').'
            '.formField('کد پستی', formInput('postal_code', 'text', $old['postal_code'] ?? '', ['placeholder' => 'کد پستی ۱۰ رقمی'], $errors), '', $errors['postal_code'] ?? '').'
            '.formField('توضیحات', formTextarea('notes', $old['notes'] ?? '', ['rows' => 3, 'placeholder' => 'توضیحات اضافی درباره واحد'], $errors), '', $errors['notes'] ?? '').'
        </div>
    </div>

    <div class="card" style="grid-column: 1 / -1;">
        <div class="card-header">
            <h3 class="card-title">عملیات</h3>
        </div>
        <div class="card-body">
            '.formActionButtons([
                ['label' => $isEdit ? 'به‌روزرسانی واحد' : 'افزودن واحد', 'type' => 'submit', 'class' => 'btn btn-primary'],
                ['label' => 'انصراف', 'href' => '/units', 'class' => 'btn btn-secondary'],
            ]).'
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
?>