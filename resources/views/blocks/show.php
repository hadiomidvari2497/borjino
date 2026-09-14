<?php
/**
 * Block Show View
 */
$title = $block['name'] ?? 'مشاهده بلوک';
$breadcrumbs = [
    ['label' => 'ساختمان‌ها', 'href' => '/buildings'],
    ['label' => 'بلوک‌ها', 'href' => '/blocks'],
    ['label' => $block['name'] ?? 'بلوک', 'href' => '#']
];
$currentRoute = '/blocks';

$pageActions = '
<div style="display: flex; gap: 0.5rem;">
    <a href="/blocks/'.$block['id'].'/edit" class="btn btn-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
        </svg>
        ویرایش
    </a>
    <a href="/blocks" class="btn btn-secondary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        بازگشت
    </a>
</div>
';

$buildingName = $block['building_name'] ?? 'ساختمان';
$buildingId = $block['building_id'] ?? 1;

$content = '
<div class="grid grid-2" style="gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">اطلاعات بلوک</h3>
        </div>
        <div class="card-body">
            <dl style="display: grid; gap: 1rem;">
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">ساختمان</dt>
                    <dd style="font-weight: 500;">'.htmlspecialchars($buildingName, ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">شماره بلوک</dt>
                    <dd style="font-weight: 600; font-size: 1.1rem;">'.htmlspecialchars($block['block_number'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">نام بلوک</dt>
                    <dd>'.htmlspecialchars($block['name'] ?? '—', ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">تعداد طبقات</dt>
                    <dd>'.htmlspecialchars(number_format($block['floor_count'] ?? 0), ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">تعداد واحدها</dt>
                    <dd>'.htmlspecialchars(number_format($block['units_count'] ?? 0), ENT_QUOTES, 'UTF-8').'</dd>
                </div>
                <div>
                    <dt class="muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;">تاریخ ثبت</dt>
                    <dd>'.($block['created_at'] ? htmlspecialchars(date('Y/m/d', strtotime($block['created_at'])), ENT_QUOTES, 'UTF-8') : '—').'</dd>
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
                <a href="/units?block_id='.$block['id'].'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    مدیریت واحدها
                </a>
                <a href="/buildings/'.$buildingId.'" class="btn btn-secondary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                        <line x1="15" y1="3" x2="15" y2="21"></line>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="3" y1="15" x2="21" y2="15"></line>
                    </svg>
                    مشاهده ساختمان
                </a>
            </div>
        </div>
    </div>
</div>
';

include __DIR__ . '/../layouts/admin.php';
?>