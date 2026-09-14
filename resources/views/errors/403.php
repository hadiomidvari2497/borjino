<?php
/**
 * 403 Forbidden Error Page
 */
$title = 'دسترسی ممنوع - ۴۰۳';
$breadcrumbs = [['label' => 'خطا', 'href' => '#']];
$currentRoute = '/403';
$showSidebarOverlay = false;

include __DIR__ . '/../layouts/admin.php';
?>

<div class="empty-state" style="min-height: 60vh;">
    <div class="empty-icon" aria-hidden="true">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
        </svg>
    </div>
    <h1 class="empty-title">۴۰۳ - دسترسی ممنوع</h1>
    <p class="empty-message">شما مجوز دسترسی به این صفحه را ندارید. لطفاً با مدیر سیستم تماس بگیرید.</p>
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
            بازگشت
        </button>
    </div>
</div>