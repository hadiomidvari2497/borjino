<?php
/**
 * Buildings List View
 */
$title = 'ساختمان‌ها';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings']
];
$currentRoute = '/buildings';

$pageActions = '
<a href="/buildings/create" class="btn btn-primary">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    افزودن ساختمان
</a>
';

$columns = [
    ['key' => 'name', 'label' => 'نام', 'sort' => true],
    ['key' => 'city', 'label' => 'شهر', 'sort' => true],
    ['key' => 'type', 'label' => 'نوع', 'sort' => true, 'type' => 'badge', 'badgeClass' => function($value) {
        $map = [
            'residential' => 'badge-primary',
            'commercial' => 'badge-info',
            'office' => 'badge-warning',
            'educational' => 'badge-success',
            'other' => 'badge-secondary',
        ];
        return $map[$value] ?? 'badge-secondary';
    }],
    ['key' => 'status', 'label' => 'وضعیت', 'sort' => true, 'type' => 'badge', 'badgeClass' => function($value) {
        $map = [
            'active' => 'badge-success',
            'inactive' => 'badge-secondary',
            'under_construction' => 'badge-warning',
        ];
        return $map[$value] ?? 'badge-secondary';
    }],
    ['key' => 'total_parking_count', 'label' => 'پارکینگ', 'sort' => true, 'type' => 'number'],
    ['key' => 'total_storage_count', 'label' => 'انبار', 'sort' => true, 'type' => 'number'],
];

$actions = [
    [
        'label' => ' مشاهده',
        'href' => function($row) { return '/buildings/'.$row['id']; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => ' ویرایش',
        'href' => function($row) { return '/buildings/'.$row['id'] . '/edit'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>',
        'class' => 'btn-icon-sm btn-secondary'
    ],
    [
        'label' => ' حذف',
        'href' => function($row) { return '/buildings/'.$row['id'] . '/delete'; },
        'icon' => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>',
        'class' => 'btn-icon-sm btn-outline-danger',
        'confirm' => 'آیا از حذف این ساختمان اطمینان دارید؟'
    ],
];

ob_start();
$tableVars = ['columns' => $columns, 'rows' => $buildings, 'actions' => $actions];
include __DIR__ . '/../components/table.php';
$tableHtml = ob_get_clean();

$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$queryParams = ['search' => $search];
ob_start();
$paginationVars = ['currentPage' => $currentPage, 'totalPages' => $totalPages, 'baseUrl' => '/buildings', 'queryParams' => $queryParams];
include __DIR__ . '/../components/pagination.php';
$paginationHtml = ob_get_clean();

$content = '
<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 1rem;">
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <form method="GET" class="search-box" style="max-width: 320px;">
                <input type="text" name="search" class="form-control search-input" placeholder="جستجوی نام، شهر یا کد پستی..." value="<?= htmlspecialchars((string) $search, ENT_QUOTES, 'UTF-8') ?>">
                <span class="search-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </span>
            </form>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <select name="status" class="form-control filter-select" onchange="this.form.submit()">
                <option value="">همه وضعیت‌ها</option>
                <option value="active" <?= ($statusFilter ?? '') === 'active' ? 'selected' : '' ?>>عالی</option>
                <option value="inactive" <?= ($statusFilter ?? '') === 'inactive' ? 'selected' : '' ?>>غیرعالی</option>
                <option value="under_construction" <?= ($statusFilter ?? '') === 'under_construction' ? 'selected' : '' ?>>در حال ساخت</option>
            </select>
            <a href="/buildings" class="btn btn-secondary">پاک کردن فیلتر</a>
        </div>
    </div>
    <div class="card-body" style="padding: 0;">
        <?= $tableHtml ?>
    </div>
    <div class="card-footer">
        <?= $paginationHtml ?>
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
