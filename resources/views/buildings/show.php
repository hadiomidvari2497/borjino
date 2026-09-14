<?php
/**
 * Building Show View
 */
$title = $building['name'] ?? 'مشاهده ساختمان';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => $building['name'] ?? 'ساختمان', 'href' => '#']
];
$currentRoute = '/buildings';

$typeLabels = [
    'residential' => 'مسکونی',
    'commercial' => 'تجاری',
    'office' => 'اداری',
    'educational' => 'آموزشی',
    'other' => 'سایر',
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

$pageActions = '
<div style="display: flex; gap: 0.5rem;">
    <a href="/buildings/'.$building['id'].'/edit" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        ویرایش
    </a>
    <a href="/buildings" class="btn btn-secondary">
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
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">نام ساختمان</dt>
                    <dd style="font-weight: 600; font-size: 1.1rem;">'.htmlspecialchars($building['name'], ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">نوع ساختمان</dt>
                    <dd><span class="badge '.($statusBadges[$building['type']] ?? 'badge-secondary').'">'.($typeLabels[$building['type']] ?? $building['type']).'</span></dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">کد پستی</dt>
                    <dd>'.htmlspecialchars($building['postal_code'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">تاریخ ساخت</dt>
                    <dd>'.($building['construction_date'] ? htmlspecialchars(date('Y/m/d', strtotime($building['construction_date'])), ENT_QUOTES, 'UTF-8') : '—').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">تعداد پارکینگ</dt>
                    <dd>'.htmlspecialchars(number_format($building['total_parking_count'] ?? 0), ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">تعداد انبار</dt>
                    <dd>'.htmlspecialchars(number_format($building['total_storage_count'] ?? 0), ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">وضعیت</dt>
                    <dd><span class="badge '.($statusBadges[$building['status']] ?? 'badge-secondary').'">'.($statusLabels[$building['status']] ?? $building['status']).'</span></dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">موقعیت جغرافیایی</h3>
        </div>
        <div class="card-body">
            <dl style="display: grid; gap: 1rem;">
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">استان</dt>
                    <dd style="font-weight: 500;">'.htmlspecialchars($building['province'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">شهر</dt>
                    <dd style="font-weight: 500;">'.htmlspecialchars($building['city'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div style="grid-column: 1 / -1;">
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">آدرس کامل</dt>
                    <dd style="white-space: pre-wrap;">'.htmlspecialchars($building['address'] ?? 'آدرس ثبت نشده', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">آمار کلی</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-2" style="gap: 1rem;">
                <div class="stat-card" style="padding: 1rem;">
                    <div class="stat-icon blue" style="margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="3" x2="15" y2="21"></line><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line></svg>
                    </div>
                    <div class="stat-label">تعداد بلوک‌ها</div>
                    <div class="stat-value">'.htmlspecialchars(number_format($building['blocks_count'] ?? 0), ENT_QUOTES, 'UTF-8').'</div>
                </div>
                <div class="stat-card" style="padding: 1rem;">
                    <div class="stat-icon green" style="margin-bottom: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    </div>
                    <div class="stat-label">تعداد واحدها</div>
                    <div class="stat-value">'.htmlspecialchars(number_format($building['units_count'] ?? 0), ENT_QUOTES, 'UTF-8').'</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">عملیات سریع</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="/blocks?building_id='.$building['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                    </svg>
                    مدیریت بلوک‌ها
                </a>
                <a href="/units?building_id='.$building['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    مدیریت واحدها
                </a>
                <a href="/charges?building_id='.$building['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                    شارژ و پرداخت‌ها
                </a>
                <a href="/contracts?building_id='.$building['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                    قراردادها
                </a>
            </div>
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
?>