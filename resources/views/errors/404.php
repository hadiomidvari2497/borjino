<?php
/**
 * 404 Not Found Error Page
 */
$title = 'صفحه یافت نشد - ۴۰۴';
$breadcrumbs = [['label' => 'خطا', 'href' => '#']];
$currentRoute = '/404';
$showSidebarOverlay = false;

include __DIR__ . '/../layouts/admin.php';
?>

<div class="empty-state" style="min-height: 60vh;">
    <div class="empty-icon" aria-hidden="true">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </div>
    <h1 class="empty-title">۴۰۴ - صفحه یافت نشد</h1>
    <p class="empty-message">صفحه‌ای که به دنبال آن هستید وجود ندارد یا ممکن است منتقل شده باشد.</p>
    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
        <a href="/dashboard" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            بازگشت به داشبورد
        </a>
        <button class="btn btn-secondary" onclick="history.back()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            برگشت به صفحه قبل
        </button>
    </div>
</div>