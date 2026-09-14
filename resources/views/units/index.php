<?php
/**
 * Units List View
 */
$title = 'واحدها';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => 'واحدها', 'href' => '/units']
];
$currentRoute = '/units';

$buildingId = $buildingId ?? null;
$blockId = $blockId ?? null;

if ($buildingId) {
    $breadcrumbs[] = ['label' => 'ساختمان', 'href' => '/buildings/'.$buildingId];
}
if ($blockId) {
    $breadcrumbs[] = ['label' => 'بلوک', 'href' => '/blocks/'.$blockId];
}

$pageActions = '
<div style="display: flex; gap: 0.5rem;">
    <a href="/units/create'.($buildingId ? '?building_id='.$buildingId : '').($blockId ? '&block_id='.$blockId : '').'" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        افزودن واحد
    </a>
</div>';

// Mock units data
$units = [
    ['id' => 1, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 1, 'block_name' => 'بلوک الف', 'unit_number' => '۱۰۱', 'floor_number' => 1, 'area_sqm' => 120, 'status' => 'sold', 'financial_status' => 'settled', 'direction' => 'north', 'created_at' => '1401-05-10'],
    ['id' => 2, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 1, 'block_name' => 'بلوک الف', 'unit_number' => '۱۰۲', 'floor_number' => 1, 'area_sqm' => 95, 'status' => 'rented', 'financial_status' => 'debtor', 'direction' => 'south', 'created_at' => '1401-05-10'],
    ['id' => 3, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 1, 'block_name' => 'بلوک الف', 'unit_number' => '۲۰۱', 'floor_number' => 2, 'area_sqm' => 140, 'status' => 'vacant', 'financial_status' => 'settled', 'direction' => 'north', 'created_at' => '1401-05-10'],
    ['id' => 4, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_id' => 2, 'block_name' => 'بلوک ب', 'unit_number' => '۱۰۱', 'floor_number' => 1, 'area_sqm' => 110, 'status' => 'sold', 'financial_status' => 'creditor', 'direction' => 'east', 'created_at' => '1401-06-15'],
    ['id' => 5, 'building_id' => 2, 'building_name' => 'مجتمع تجاری پارسیان', 'block_id' => 4, 'block_name' => 'بلوک تجاری ۱', 'unit_number' => 'م-۱', 'floor_number' => 1, 'area_sqm' => 80, 'status' => 'rented', 'financial_status' => 'settled', 'direction' => 'west', 'created_at' => '1402-02-01'],
];

$statusLabels = [
    'sold' => 'فروخته شده',
    'rented' => 'اجاره‌داده شده',
    'vacant' => 'خالی',
    'under_repair' => 'در تعمیر',
];

$statusBadges = [
    'sold' => 'badge-success',
    'rented' => 'badge-info',
    'vacant' => 'badge-warning',
    'under_repair' => 'badge-danger',
];

$financialLabels = [
    'settled' => 'تسویه',
    'debtor' => 'بدهکار',
    'creditor' => 'بستانکار',
];

$financialBadges = [
    'settled' => 'badge-success',
    'debtor' => 'badge-danger',
    'creditor' => 'badge-info',
];

$directionLabels = [
    'north' => 'شمالی',
    'south' => 'جنوبی',
    'east' => 'شرقی',
    'west' => 'غربی',
];

$columns = [
    ['key' => 'building_name', 'label' => 'ساختمان', 'sort' => true],
    ['key' => 'block_name', 'label' => 'بلوک', 'sort' => true],
    ['key' => 'unit_number', 'label' => 'شماره واحد', 'sort' => true],
    ['key' => 'floor_number', 'label' => 'طبقه', 'sort' => true, 'type' => 'number'],
    ['key' => 'area_sqm', 'label' => 'متراژ (م²)', 'sort' => true, 'type' => 'number'],
    ['key' => 'status', 'label' => 'وضعیت', 'sort' => true, 'type' => 'badge', 'badgeClass' => function($v) use ($statusBadges) { return $statusBadges[$v] ?? 'badge-secondary'; }, 'format' => function($v) use ($statusLabels) { return $statusLabels[$v] ?? $v; }],
    ['key' => 'financial_status', 'label' => 'وضعیت مالی', 'sort' => true, 'type' => 'badge', 'badgeClass' => function($v) use ($financialBadges) { return $financialBadges[$v] ?? 'badge-secondary'; }, 'format' => function($v) use ($financialLabels) { return $financialLabels[$v] ?? $v; }],
    ['key' => 'direction', 'label' => 'جهت', 'sort' => true, 'format' => function($v) use ($directionLabels) { return $directionLabels[$v] ?? $v; }],
];

$actions = [
    [
        'label' => 'مشاهده',
        'href' => function($row) { return '/units/'.$row['id']; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'ویرایش',
        'href' => function($row) { return '/units/'.$row['id'].'/edit'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'قراردادها',
        'href' => function($row) { return '/contracts?unit_id='.$row['id']; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'حذف',
        'href' => function($row) { return '/units/'.$row['id'].'/delete'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
        'class' => 'btn-icon-sm btn-outline-danger',
        'confirm' => 'آیا از حذف این واحد اطمینان دارید؟'
    ],
];

ob_start();
$tableVars = ['columns' => $columns, 'rows' => $units, 'actions' => $actions];
include __DIR__ . '/../components/table.php';
$tableHtml = ob_get_clean();

ob_start();
$paginationVars = ['currentPage' => 1, 'totalPages' => 1, 'baseUrl' => '/units', 'queryParams' => []];
include __DIR__ . '/../components/pagination.php';
$paginationHtml = ob_get_clean();

$content = '
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <form method="GET" class="search-box" style="max-width: 320px;">
                <input type="text" name="search" class="form-control search-input" placeholder="جستجوی واحدها..." value="">
                <span class="search-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
            </form>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <form method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <select name="building_id" class="form-control filter-select">
                    <option value="">همه ساختمان‌ها</option>
                    <option value="1" '.($buildingId == 1 ? 'selected' : '').'>ساختمان سپهر</option>
                    <option value="2" '.($buildingId == 2 ? 'selected' : '').'>مجتمع تجاری پارسیان</option>
                </select>
                <select name="block_id" class="form-control filter-select">
                    <option value="">همه بلوک‌ها</option>
                    <option value="1" '.($blockId == 1 ? 'selected' : '').'>بلوک الف</option>
                    <option value="2" '.($blockId == 2 ? 'selected' : '').'>بلوک ب</option>
                    <option value="4" '.($blockId == 4 ? 'selected' : '').'>بلوک تجاری ۱</option>
                </select>
                <select name="status" class="form-control filter-select">
                    <option value="">همه وضعیت‌ها</option>
                    <option value="sold">فروخته شده</option>
                    <option value="rented">اجاره‌داده شده</option>
                    <option value="vacant">خالی</option>
                    <option value="under_repair">در تعمیر</option>
                </select>
                <select name="financial_status" class="form-control filter-select">
                    <option value="">همه وضعیت‌های مالی</option>
                    <option value="settled">تسویه</option>
                    <option value="debtor">بدهکار</option>
                    <option value="creditor">بستانکار</option>
                </select>
                <button type="submit" class="btn btn-secondary">فیلتر</button>
                <a href="/units" class="btn btn-secondary">پاک کردن</a>
            </form>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        '.$tableHtml.'
    </div>
    <div class="card-footer">
        '.$paginationHtml.'
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
?>