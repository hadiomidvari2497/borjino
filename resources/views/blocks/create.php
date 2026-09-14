<?php
/**
 * Block Create/Edit View
 */
include __DIR__ . '/../components/form.php';

$isEdit = isset($block) && !empty($block['id']);
$title = $isEdit ? 'ویرایش بلوک' : 'افزودن بلوک جدید';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => 'بلوک‌ها', 'href' => '/blocks'],
    ['label' => $isEdit ? 'ویرایش' : 'افزودن جدید', 'href' => '#']
];
$currentRoute = '/blocks';

$pageActions = '
<a href="/blocks" class="btn btn-secondary">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    بازگشت
</a>
';

$formAction = $isEdit ? '/blocks/'.$block['id'] : '/blocks';
$formMethod = 'POST';

$old = $old ?? ($isEdit ? $block : []);
$errors = $errors ?? [];

$buildings = [
    1 => 'ساختمان سپهر',
    2 => 'مجتمع تجاری پارسیان',
    3 => 'ساختمان اداری آفتاب',
    4 => 'مدرسه شهید بهشتی',
    5 => 'ساختمان مسکونی گلسار',
];

$content = '
<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3 class="card-title">اطلاعات بلوک</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="'.$formAction.'" novalidate>
            '.($isEdit ? formHidden('_method', 'PUT') : '').'
            '.formField('ساختمان *', formSelect('building_id', $buildings, $old['building_id'] ?? '', ['required' => true], $errors), 'ساختمان مربوطه را انتخاب کنید', $errors['building_id'] ?? '', true).'
            '.formField('شماره بلوک *', formInput('block_number', 'number', $old['block_number'] ?? '', ['required' => true, 'min' => 1, 'placeholder' => 'مثال: ۱'], $errors), 'شماره بلوک باید در هر ساختمان یکتا باشد', $errors['block_number'] ?? '', true).'
            '.formField('نام بلوک', formInput('name', 'text', $old['name'] ?? '', ['placeholder' => 'مثال: بلوک الف، بلوک شمالی، ...'], $errors), '', $errors['name'] ?? '').'
            '.formField('تعداد طبقات *', formInput('floor_count', 'number', $old['floor_count'] ?? '', ['required' => true, 'min' => 1, 'placeholder' => 'تعداد طبقات بلوک'], $errors), '', $errors['floor_count'] ?? '', true).'
        </div>
    </div>
    <div class="card-footer">
        '.formActionButtons([
            ['label' => $isEdit ? 'به‌روزرسانی بلوک' : 'افزودن بلوک', 'type' => 'submit', 'class' => 'btn btn-primary'],
            ['label' => 'انصراف', 'href' => '/blocks', 'class' => 'btn btn-secondary'],
        ]).'
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
?>