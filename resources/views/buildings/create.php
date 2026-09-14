<?php
/**
 * Building Create View
 */
include __DIR__ . '/../components/form.php';

$title = 'افزودن ساختمان جدید';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => 'افزودن جدید', 'href' => '/buildings/create']
];
$currentRoute = '/buildings/create';

$pageActions = '
<a href="/buildings" class="btn btn-secondary">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    بازگشت
</a>
';

$formAction = '/buildings';
$formMethod = 'POST';

// Form data (from old input or empty)
$old = $old ?? [];
$errors = $errors ?? [];

$buildingTypes = [
    'residential' => 'مسکونی',
    'commercial' => 'تجاری',
    'office' => 'اداری',
    'educational' => 'آموزشی',
    'other' => 'سایر',
];

$provinces = [
    'تهران', 'البرز', 'مازندران', 'گیلان', 'قزوین', 'قم', 'مرکزی',
    'همدان', 'کردستان', 'کرمانشاه', 'ایلام', 'لرستان', 'خوزستان',
    'چهارمحال و بختیاری', 'کوهکیلویه و بویراحمد', 'فارس', 'بوشهر',
    'هرمزگان', 'سیستان و بلوچستان', 'کرمان', 'یزد', 'اصفهان',
    'سمنان', 'خراسان رضوی', 'خراسان شمالی', 'خراسان جنوبی',
    'آذربایجان شرقی', 'آذربایجان غربی', 'اردبیل', 'زنجان', 'قازوین'
];

$content = '
<div class="grid grid-2" style="gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">اطلاعات اصلی</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="'.$formAction.'" novalidate>
                '.formField('نام ساختمان *', formInput('name', 'text', $old['name'] ?? '', ['required' => true, 'placeholder' => 'مثال: ساختمان سپهر'], $errors), '', $errors['name'] ?? '', true).'
                '.formField('نوع ساختمان *', formSelect('type', $buildingTypes, $old['type'] ?? '', ['required' => true], $errors), '', $errors['type'] ?? '', true).'
                '.formField('کد پستی', formInput('postal_code', 'text', $old['postal_code'] ?? '', ['placeholder' => 'کد پستی ۱۰ رقمی'], $errors), '', $errors['postal_code'] ?? '').'
                '.formField('تاریخ ساخت', formInput('construction_date', 'date', $old['construction_date'] ?? '', [], $errors), '', $errors['construction_date'] ?? '').'
                '.formField('تعداد پارکینگ', formInput('total_parking_count', 'number', $old['total_parking_count'] ?? '0', ['min' => 0], $errors), '', $errors['total_parking_count'] ?? '').'
                '.formField('تعداد انبار', formInput('total_storage_count', 'number', $old['total_storage_count'] ?? '0', ['min' => 0], $errors), '', $errors['total_storage_count'] ?? '').'
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">آدرس و موقعیت</h3>
        </div>
        <div class="card-body">
            '.formField('استان *', formSelect('province', array_combine($provinces, $provinces), $old['province'] ?? '', ['required' => true], $errors), '', $errors['province'] ?? '', true).'
            '.formField('شهر *', formInput('city', 'text', $old['city'] ?? '', ['required' => true, 'placeholder' => 'نام شهر'], $errors), '', $errors['city'] ?? '', true).'
            '.formField('آدرس کامل', formTextarea('address', $old['address'] ?? '', ['rows' => 3, 'placeholder' => 'آدرس دقیق ساختمان'], $errors), '', $errors['address'] ?? '').'
        </div>
    </div>

    <div class="card" style="grid-column: 1 / -1;">
        <div class="card-header">
            <h3 class="card-title">عملیات</h3>
        </div>
        <div class="card-body">
            '.formActionButtons([
                ['label' => 'ذخیره ساختمان', 'type' => 'submit', 'class' => 'btn btn-primary'],
                ['label' => 'انصراف', 'href' => '/buildings', 'class' => 'btn btn-secondary'],
            ]).'
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
?>