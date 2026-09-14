<?php
/**
 * Blocks List View
 */
$title = 'بلوک‌ها';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => 'بلوک‌ها', 'href' => '/blocks']
];
$currentRoute = '/blocks';

$buildingId = $buildingId ?? null;
$buildingName = $buildingName ?? 'همه ساختمان‌ها';

if ($buildingId) {
    $breadcrumbs = array_merge($breadcrumbs, [['label' => $buildingName, 'href' => '/buildings/'.$buildingId]]);
}

$pageActions = '
<div style="display: flex; gap: 0.5rem;">
    <a href="/blocks/create'.($buildingId ? '?building_id='.$buildingId : '').'" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        افزودن بلوک
    </a>
</div>';

// Mock blocks data
$blocks = [
    ['id' => 1, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_number' => 1, 'name' => 'بلوک الف', 'floor_count' => 8, 'units_count' => 16, 'created_at' => '1401-03-15'],
    ['id' => 2, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_number' => 2, 'name' => 'بلوک ب', 'floor_count' => 8, 'units_count' => 16, 'created_at' => '1401-03-15'],
    ['id' => 3, 'building_id' => 1, 'building_name' => 'ساختمان سپهر', 'block_number' => 3, 'name' => 'بلوک ج', 'floor_count' => 6, 'units_count' => 12, 'created_at' => '1401-04-20'],
    ['id' => 4, 'building_id' => 2, 'building_name' => 'مجتمع تجاری پارسیان', 'block_number' => 1, 'name' => 'بلوک تجاری ۱', 'floor_count' => 4, 'units_count' => 20, 'created_at' => '1402-01-10'],
    ['id' => 5, 'building_id' => 2, 'building_name' => 'مجتمع تجاری پارسیان', 'block_number' => 2, 'name' => 'بلوک تجاری ۲', 'floor_count' => 4, 'units_count' => 12, 'created_at' => '1402-01-10'],
];

$columns = [
    ['key' => 'building_name', 'label' => 'ساختمان', 'sort' => true],
    ['key' => 'block_number', 'label' => 'شماره بلوک', 'sort' => true, 'type' => 'number'],
    ['key' => 'name', 'label' => 'نام بلوک', 'sort' => true],
    ['key' => 'floor_count', 'label' => 'تعداد طبقات', 'sort' => true, 'type' => 'number'],
    ['key' => 'units_count', 'label' => 'تعداد واحدها', 'sort' => true, 'type' => 'number'],
    ['key' => 'created_at', 'label' => 'تاریخ ثبت', 'sort' => true, 'type' => 'date'],
];

$actions = [
    [
        'label' => 'مشاهده',
        'href' => function($row) { return '/blocks/'.$row['id']; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'ویرایش',
        'href' => function($row) { return '/blocks/'.$row['id'].'/edit'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'واحدها',
        'href' => function($row) { return '/units?block_id='.$row['id']; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'حذف',
        'href' => function($row) { return '/blocks/'.$row['id'].'/delete'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
        'class' => 'btn-icon-sm btn-outline-danger',
        'confirm' => 'آیا از حذف این بلوک اطمینان دارید؟'
    ],
];

ob_start();
$tableVars = ['columns' => $columns, 'rows' => $blocks, 'actions' => $actions];
include __DIR__ . '/../components/table.php';
$tableHtml = ob_get_clean();

ob_start();
$paginationVars = ['currentPage' => 1, 'totalPages' => 1, 'baseUrl' => '/blocks', 'queryParams' => []];
include __DIR__ . '/../components/pagination.php';
$paginationHtml = ob_get_clean();

$content = '
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <form method="GET" class="search-box" style="max-width: 320px;">
                <input type="text" name="search" class="form-control search-input" placeholder="جستجوی بلوک‌ها..." value="">
                <span class="search-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
                '.($buildingId ? '<input type="hidden" name="building_id" value="'.htmlspecialchars($buildingId, ENT_QUOTES, 'UTF-8').'">' : '').'
            </form>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <form method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <select name="building_id" class="form-control filter-select">
                    <option value="">همه ساختمان‌ها</option>
                    <option value="1" '.($buildingId == 1 ? 'selected' : '').'>ساختمان سپهر</option>
                    <option value="2" '.($buildingId == 2 ? 'selected' : '').'>مجتمع تجاری پارسیان</option>
                    <option value="3" '.($buildingId == 3 ? 'selected' : '').'>ساختمان اداری آفتاب</option>
                </select>
                <button type="submit" class="btn btn-secondary">فیلتر</button>
                <a href="/blocks" class="btn btn-secondary">پاک کردن</a>
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