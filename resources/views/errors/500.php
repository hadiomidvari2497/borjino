<?php
/**
 * 500 Server Error Page
 */
$title = 'خطای سرور - ۵۰۰';
$breadcrumbs = [['label' => 'خطا', 'href' => '#']];
$currentRoute = '/500';
$showSidebarOverlay = false;

include __DIR__ . '/../layouts/admin.php';
?>

<div class="empty-state" style="min-height: 60vh;">
    <div class="empty-icon" aria-hidden="true">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M12 9v4M12 17h.01"></path>
            <path d="M5 12a7 7 0 1 0 14 0 7 7 0 1 0-14 0"></path>
        </svg>
    </div>
    <h1 class="empty-title">۵۰۰ - خطای سرور</h1>
    <p class="empty-message">متأسفانه خطایی در سرور رخ داده است. تیم فنی از این موضوع مطلع شده و در حال رفع آن است.</p>
    <div style="display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap;">
        <a href="/dashboard" class="btn btn-primary">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            بازگشت به داشبورد
        </a>
        <button class="btn btn-secondary" onclick="location.reload()">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="1 4 1 10 7 10"></polyline>
                <polyline points="23 20 23 14 17 14"></polyline>
                <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
            </svg>
            تلاش مجدد
        </button>
    </div>
    <details style="margin-top: 2rem; text-align: left; max-width: 600px; margin-left: auto; margin-right: auto;">
        <summary style="cursor: pointer; color: var(--text-muted); font-size: 0.875rem;">جزئیات فنی (برای توسعه‌دهندگان)</summary>
        <pre style="margin-top: 1rem; padding: 1rem; background: var(--surface-hover); border-radius: var(--radius); overflow: auto; font-size: 0.75rem; color: var(--text-muted);">
<?php
if (isset($exception)) {
    echo htmlspecialchars(get_class($exception).': '.$exception->getMessage()."\n".$exception->getTraceAsString(), ENT_QUOTES, 'UTF-8');
} else {
    echo 'خطای نامشخص';
}
?>
        </pre>
    </details>
</div>