<?php
/**
 * Unit Show View
 */
$title = 'واحد ' . ($unit['unit_number'] ?? '—');
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => 'واحدها', 'href' => '/units'],
    ['label' => 'واحد ' . ($unit['unit_number'] ?? '—'), 'href' => '#']
];
$currentRoute = '/units';

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

$buildingName = $unit['building_name'] ?? 'ساختمان';
$blockName = $unit['block_name'] ?? 'بلوک';
$blockId = $unit['block_id'] ?? 1;
$status = $unit['status'] ?? 'vacant';
$financialStatus = $unit['financial_status'] ?? 'settled';
$direction = $unit['direction'] ?? '';

$pageActions = '
<div style="display: flex; gap: 0.5rem;">
    <a href="/units/'.$unit['id'].'/edit" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        ویرایش
    </a>
    <a href="/units" class="btn btn-secondary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        بازگشت
    </a>
</div>
';

$content = '
<div class="grid grid-2" style="gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">اطلاعات اصلی</h3>
        </div>
        <div class="card-body">
            <dl style="display: grid; gap: 1rem;">
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">ساختمان</dt>
                    <dd style="font-weight: 500;">'.htmlspecialchars($buildingName, ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">بلوک</dt>
                    <dd style="font-weight: 500;">'.htmlspecialchars($blockName, ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">شماره واحد</dt>
                    <dd style="font-weight: 600; font-size: 1.1rem;">'.htmlspecialchars($unit['unit_number'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">طبقه</dt>
                    <dd>'.htmlspecialchars($unit['floor_number'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">متراژ (متر مربع)</dt>
                    <dd>'.htmlspecialchars(number_format($unit['area_sqm'] ?? 0, 2), ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">وضعیت</dt>
                    <dd><span class="badge '.($statusBadges[$status] ?? 'badge-secondary').'">'.($statusLabels[$status] ?? $status).'</span></dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">وضعیت مالی</dt>
                    <dd><span class="badge '.($financialBadges[$financialStatus] ?? 'badge-secondary').'">'.($financialLabels[$financialStatus] ?? $financialStatus).'</span></dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">جهت</dt>
                    <dd>'.($direction ? ($directionLabels[$direction] ?? $direction) : '—').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">کد پستی</dt>
                    <dd>'.htmlspecialchars($unit['postal_code'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div style="grid-column: 1 / -1;">
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">توضیحات</dt>
                    <dd style="white-space: pre-wrap;">'.htmlspecialchars($unit['notes'] ?? 'توضیحات ثبت نشده', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">عملیات سریع</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="/contracts?unit_id='.$unit['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    قراردادها
                </a>
                <a href="/charges?unit_id='.$unit['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    شارژ و پرداخت‌ها
                </a>
                <a href="/members?unit_id='.$unit['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    اعضا
                </a>
                <a href="/blocks/'.$blockId.'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                    </svg>
                    مشاهده بلوک
                </a>
            </div>
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
?>