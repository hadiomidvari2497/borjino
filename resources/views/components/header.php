<?php
/**
 * Header Component
 * Usage: include 'components/header.php' with $breadcrumbs, $pageTitle, $user variables
 */
$breadcrumbs = $breadcrumbs ?? [];
$pageTitle = $pageTitle ?? 'داشبورد';
$user = $user ?? ['username' => 'Admin', 'role' => 'مدیر سیستم'];

$defaultBreadcrumbs = [['label' => 'خانه', 'href' => '/dashboard']];
$allBreadcrumbs = array_merge($defaultBreadcrumbs, $breadcrumbs);

$notificationCount = $notificationCount ?? 3;
?>

<header class="header" role="banner">
    <div class="header-left">
        <button class="mobile-menu-toggle" data-dropdown-toggle aria-label="باز کردن منو" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </button>
        <nav class="breadcrumb" aria-label="مسیر جاری">
            <ol style="display: flex; align-items: center; gap: 0.5rem; list-style: none; margin: 0; padding: 0;">
                <?php foreach ($allBreadcrumbs as $index => $crumb): ?>
                    <li class="breadcrumb-item <?= ($index === array_key_last($allBreadcrumbs)) ? 'active' : '' ?>"
                        <?= ($index === array_key_last($allBreadcrumbs)) ? 'aria-current="page"' : '' ?>>
                        <?php if ($index !== array_key_last($allBreadcrumbs)): ?>
                            <a href="<?= htmlspecialchars($crumb['href'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                            <span class="breadcrumb-separator" aria-hidden="true">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="15 18 9 12 15 6"></polyline>
                                </svg>
                            </span>
                        <?php else: ?>
                            <span><?= htmlspecialchars($crumb['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ol>
        </nav>
    </div>
    <div class="header-right">
        <button class="header-btn theme-toggle" data-theme-toggle aria-label="تغییر تم" title="تغییر تم">
            <span class="icon-moon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </span>
            <span class="icon-sun" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
            </span>
        </button>
        <div class="dropdown">
            <button class="header-btn" data-dropdown-toggle aria-label="اعلان‌ها" aria-expanded="false" aria-haspopup="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                <?php if ($notificationCount > 0): ?>
                    <span class="badge"><?= min($notificationCount, 99) ?></span>
                <?php endif; ?>
            </button>
            <div class="dropdown-menu" role="menu">
                <div class="dropdown-item" style="padding: 0.75rem; font-weight: 600; color: var(--text);">
                    اعلان‌ها
                    <?php if ($notificationCount > 0): ?>
                        <span class="badge badge-primary" style="margin-right: auto;"><?= $notificationCount ?></span>
                    <?php endif; ?>
                </div>
                <div class="dropdown-divider"></div>
                <div class="dropdown-item" role="menuitem">
                    <span class="dropdown-item-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        </svg>
                    </span>
                    <span>شارژ جدید برای واحد ۱۲۳ صادر شد</span>
                </div>
                <div class="dropdown-item" role="menuitem">
                    <span class="dropdown-item-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </span>
                    <span>پرداخت از واحد ۴۵۶ تایید گردید</span>
                </div>
                <div class="dropdown-item" role="menuitem">
                    <span class="dropdown-item-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                            <line x1="12" y1="9" x2="12" y2="13"></line>
                            <line x1="12" y1="17" x2="12.01" y2="17"></line>
                        </svg>
                    </span>
                    <span>تعمیرات در بلوک ب برنامه‌ریزی شد</span>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/notifications" role="menuitem" style="text-align: center; color: var(--primary);">
                    مشاهده تمام اعلان‌ها
                </a>
            </div>
        </div>
        <div class="dropdown">
            <button class="header-btn" data-dropdown-toggle aria-label="منوی کاربر" aria-expanded="false" aria-haspopup="true">
                <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">
                    <?= htmlspecialchars(mb_substr($user['username'] ?? 'A', 0, 1, 'UTF-8'), ENT_QUOTES, 'UTF-8') ?>
                </div>
            </button>
            <div class="dropdown-menu" role="menu" style="min-width: 220px;">
                <div class="dropdown-item" style="padding: 0.75rem; flex-direction: column; align-items: flex-end; gap: 0.25rem;">
                    <div style="font-weight: 600;"><?= htmlspecialchars($user['username'] ?? 'Admin', ENT_QUOTES, 'UTF-8') ?></div>
                    <div style="font-size: 0.75rem; color: var(--text-muted);"><?= htmlspecialchars($user['role'] ?? 'مدیر سیستم', ENT_QUOTES, 'UTF-8') ?></div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/profile" role="menuitem">
                    <span class="dropdown-item-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </span>
                    پروفایل کاربری
                </a>
                <a class="dropdown-item" href="/settings" role="menuitem">
                    <span class="dropdown-item-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </span>
                    تنظیمات
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="/logout" style="width: 100%;">
                    <button class="dropdown-item danger" type="submit" role="menuitem">
                        <span class="dropdown-item-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </span>
                        خروج از سیستم
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>