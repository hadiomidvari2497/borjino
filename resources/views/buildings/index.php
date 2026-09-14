<?php
/**
 * Buildings List View
 */
$title = 'ساختمان‌ها';
$breadcrumbs = [['label' => 'ساختمان‌ها', 'href' => '/buildings']];
$currentRoute = '/buildings';

$pageActions = '
<div style="display: flex; gap: 0.5rem;">
    <a href="/buildings/create" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
        افزودن ساختمان
    </a>
</div>';

// Mock buildings data
$buildings = [
    [
        'id' => 1,
        'name' => 'ساختمان سپهر',
        'type' => 'residential',
        'province' => 'تهران',
        'city' => 'تهران',
        'blocks_count' => 4,
        'units_count' => 48,
        'construction_date' => '1398-03-15',
        'status' => 'active'
    ],
    [
        'id' => 2,
        'name' => 'مجتمع تجاری پارسیان',
        'type' => 'commercial',
        'province' => 'تهران',
        'city' => 'شهریار',
        'blocks_count' => 2,
        'units_count' => 32,
        'construction_date' => '1400-06-20',
        'status' => 'active'
    ],
    [
        'id' => 3,
        'name' => 'ساختمان اداری آفتاب',
        'type' => 'office',
        'province' => 'البرز',
        'city' => 'کرج',
        'blocks_count' => 1,
        'units_count' => 15,
        'construction_date' => '1399-11-10',
        'status' => 'under_construction'
    ],
    [
        'id' => 4,
        'name' => 'مدرسه شهید بهشتی',
        'type' => 'educational',
        'province' => 'تهران',
        'city' => 'اسلامشهر',
        'blocks_count' => 3,
        'units_count' => 24,
        'construction_date' => '1395-01-01',
        'status' => 'active'
    ],
    [
        'id' => 5,
        'name' => 'ساختمان مسکونی گلسار',
        'type' => 'residential',
        'province' => 'مازندران',
        'city' => 'ساری',
        'blocks_count' => 2,
        'units_count' => 18,
        'construction_date' => '1401-04-12',
        'status' => 'inactive'
    ],
];

$typeLabels = [
    'residential' => 'مسکونی',
    'commercial' => 'تجاری',
    'office' => 'اداری',
    'educational' => 'آموزشی',
    'other' => 'سایر',
];

$typeBadges = [
    'residential' => 'badge-primary',
    'commercial' => 'badge-success',
    'office' => 'badge-info',
    'educational' => 'badge-warning',
    'other' => 'badge-secondary',
];

$statusLabels = [
    'active' => 'فعال',
    'inactive' => 'غیرفعال',
    'under_construction' => 'در حال ساخت',
];

$statusBadges = [
    'active' => 'badge-success',
    'inactive' => 'badge-secondary',
    'under_construction' => 'badge-warning',
];

$columns = [
    ['key' => 'name', 'label' => 'نام ساختمان', 'sort' => true],
    ['key' => 'type', 'label' => 'نوع', 'sort' => true, 'type' => 'badge', 'badgeClass' => function($v) use ($typeLabels, $typeBadges) { return $typeBadges[$v] ?? 'badge-secondary'; }, 'format' => function($v) use ($typeLabels) { return $typeLabels[$v] ?? $v; }],
    ['key' => 'province', 'label' => 'استان', 'sort' => true],
    ['key' => 'city', 'label' => 'شهر', 'sort' => true],
    ['key' => 'blocks_count', 'label' => 'تعداد بلوک', 'sort' => true, 'type' => 'number'],
    ['key' => 'units_count', 'label' => 'تعداد واحد', 'sort' => true, 'type' => 'number'],
    ['key' => 'construction_date', 'label' => 'تاریخ ساخت', 'sort' => true, 'type' => 'date'],
    ['key' => 'status', 'label' => 'وضعیت', 'sort' => true, 'type' => 'badge', 'badgeClass' => function($v) use ($statusBadges) { return $statusBadges[$v] ?? 'badge-secondary'; }, 'format' => function($v) use ($statusLabels) { return $statusLabels[$v] ?? $v; }],
];

$actions = [
    [
        'label' => 'مشاهده',
        'href' => function($row) { return '/buildings/'.$row['id']; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'ویرایش',
        'href' => function($row) { return '/buildings/'.$row['id'].'/edit'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => 'حذف',
        'href' => function($row) { return '/buildings/'.$row['id'].'/delete'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
        'class' => 'btn-icon-sm btn-outline-danger',
        'confirm' => 'آیا از حذف این ساختمان اطمینان دارید؟'
    ],
];

// Search and filter form
$searchForm = '
<form method="GET" class="search-box" style="max-width: 320px;">
    <input type="text" name="search" class="form-control search-input" placeholder="جستجوی ساختمان‌ها..." value="">
    <span class="search-icon" aria-hidden="true">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </span>
</form>
';

$filterForm = '
<form method="GET" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
    <select name="type" class="form-control filter-select">
        <option value="">همه انواع</option>
        <option value="residential">مسکونی</option>
        <option value="commercial">تجاری</option>
        <option value="office">اداری</option>
        <option value="educational">آموزشی</option>
    </select>
    <select name="status" class="form-control filter-select">
        <option value="">همه وضعیت‌ها</option>
        <option value="active">فعال</option>
        <option value="inactive">غیرفعال</option>
        <option value="under_construction">در حال ساخت</option>
    </select>
    <button type="submit" class="btn btn-secondary">اعمال فیلتر</button>
    <a href="/buildings" class="btn btn-secondary">پاک کردن</a>
</form>
';

ob_start();
$tableVars = ['columns' => $columns, 'rows' => $buildings, 'actions' => $actions];
include __DIR__ . '/../components/table.php';
$tableHtml = ob_get_clean();

ob_start();
$paginationVars = ['currentPage' => 1, 'totalPages' => 1, 'baseUrl' => '/buildings', 'queryParams' => []];
include __DIR__ . '/../components/pagination.php';
$paginationHtml = ob_get_clean();

$content = '
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            '.$searchForm.'
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            '.$filterForm.'
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