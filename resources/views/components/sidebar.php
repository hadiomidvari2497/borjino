<?php
/**
 * Sidebar Component
 * Usage: include 'components/sidebar.php' with $currentRoute variable
 */
$currentRoute = $currentRoute ?? '/dashboard';
$navItems = [
    ['label' => 'داشبورد', 'icon' => 'home', 'href' => '/dashboard', 'active' => $currentRoute === '/dashboard'],
    ['label' => 'ساختمان‌ها', 'icon' => 'building', 'href' => '/buildings', 'active' => str_starts_with($currentRoute, '/buildings')],
    ['label' => 'بلوک‌ها', 'icon' => 'grid', 'href' => '/blocks', 'active' => str_starts_with($currentRoute, '/blocks')],
    ['label' => 'واحدها', 'icon' => 'home', 'href' => '/units', 'active' => str_starts_with($currentRoute, '/units')],
    ['label' => 'اعضای ساختمان', 'icon' => 'users', 'href' => '/members', 'active' => str_starts_with($currentRoute, '/members')],
    ['label' => 'پرسنل', 'icon' => 'user-check', 'href' => '/personnel', 'active' => str_starts_with($currentRoute, '/personnel')],
    ['label' => 'قراردادها', 'icon' => 'file-text', 'href' => '/contracts', 'active' => str_starts_with($currentRoute, '/contracts')],
    ['label' => 'شارژ و پرداخت‌ها', 'icon' => 'credit-card', 'href' => '/charges', 'active' => str_starts_with($currentRoute, '/charges')],
    ['label' => 'گزارش‌ها', 'icon' => 'bar-chart', 'href' => '/reports', 'active' => str_starts_with($currentRoute, '/reports')],
    ['label' => 'کاربران و دسترسی', 'icon' => 'shield', 'href' => '/users', 'active' => str_starts_with($currentRoute, '/users')],
    ['label' => 'تنظیمات', 'icon' => 'settings', 'href' => '/settings', 'active' => str_starts_with($currentRoute, '/settings')],
];

$icons = [
    'home' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
    'building' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="3" x2="15" y2="21"></line><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line></svg>',
    'grid' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>',
    'users' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
    'user-check' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><polyline points="17 11 19 13 22 10"></polyline></svg>',
    'file-text' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
    'credit-card' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>',
    'bar-chart' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>',
    'shield' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>',
    'settings' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>',
];
?>

<aside class="sidebar" role="navigation" aria-label="منوی اصلی">
    <div class="brand">
        <span class="brand-icon">ب</span>
        <span>برجینو</span>
    </div>
    <nav class="nav" aria-label="منوی ناوبری">
        <?php foreach ($navItems as $item): ?>
            <a class="nav-item <?= $item['active'] ? 'active' : '' ?>"
               href="<?= htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8') ?>"
               <?= $item['active'] ? 'aria-current="page"' : '' ?>>
                <span class="nav-icon" aria-hidden="true"><?= $icons[$item['icon']] ?? '' ?></span>
                <span><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar" aria-hidden="true">
                <?= htmlspecialchars(mb_substr($user['username'] ?? 'A', 0, 1, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>
            </div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($user['username'] ?? 'کاربر', ENT_QUOTES, 'UTF-8') ?></div>
                <div class="user-role"><?= htmlspecialchars($user['role'] ?? 'مدیر سیستم', ENT_QUOTES, 'UTF-8') ?></div>
            </div>
        </div>
    </div>
</aside>

<?php if (isset($showOverlay) && $showOverlay): ?>
<div class="sidebar-overlay" aria-hidden="true"></div>
<?php endif; ?>