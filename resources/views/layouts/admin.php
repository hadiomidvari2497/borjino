<?php
/**
 * Admin Layout
 * Main layout for authenticated pages
 *
 * Variables:
 * - $title: Page title
 * - $breadcrumbs: Array of breadcrumb items [{label, href}]
 * - $content: Main content HTML
 * - $user: User data [username, role, avatar]
 * - $notificationCount: Number of notifications
 * - $pageActions: Additional actions for page header
 * - $extraHead: Extra head content (CSS, meta tags)
 * - $extraScripts: Extra scripts at bottom
 */
$title = $title ?? 'برجینو - پنل مدیریت';
$breadcrumbs = $breadcrumbs ?? [];
$content = $content ?? '';
$user = $user ?? ['username' => 'Admin', 'role' => 'مدیر سیستم'];
$notificationCount = $notificationCount ?? 0;
$pageActions = $pageActions ?? '';
$extraHead = $extraHead ?? '';
$extraScripts = $extraScripts ?? '';
$currentRoute = $currentRoute ?? '/dashboard';
$showSidebarOverlay = true;

$defaultBreadcrumbs = [['label' => 'داشبورد', 'href' => '/dashboard']];
$allBreadcrumbs = array_merge($defaultBreadcrumbs, $breadcrumbs);
?>
<!doctype html>
<html lang="fa" dir="rtl" data-theme="<?= htmlspecialchars($_COOKIE['theme'] ?? 'light', ENT_QUOTES, 'UTF-8') ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="برجینو - سامانه مدیریت ساختمان">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Preconnect for fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="/assets/css/app.css">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>ب</text></svg>">

    <?= $extraHead ?>
</head>
<body>
    <div class="layout">
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay" aria-hidden="true"></div>

        <!-- Sidebar -->
        <?php include __DIR__ . '/../components/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main" role="main">
            <!-- Header -->
            <?php
            $headerBreadcrumbs = $breadcrumbs;
            $headerUser = $user;
            $headerNotificationCount = $notificationCount;
            include __DIR__ . '/../components/header.php';
            ?>

            <!-- Page Content -->
            <div class="content">
                <?php if ($pageActions || !empty($breadcrumbs)): ?>
                    <div class="page-header">
                        <div>
                            <h1 class="page-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h1>
                            <?php if (!empty($breadcrumbs)): ?>
                                <nav class="breadcrumb" aria-label="مسیر جاری" style="margin-top: 0.5rem;">
                                    <ol style="display: flex; align-items: center; gap: 0.5rem; list-style: none; margin: 0; padding: 0;">
                                        <li class="breadcrumb-item">
                                            <a href="/dashboard">داشبورد</a>
                                            <span class="breadcrumb-separator" aria-hidden="true">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <polyline points="15 18 9 12 15 6"></polyline>
                                                </svg>
                                            </span>
                                        </li>
                                        <?php foreach ($breadcrumbs as $index => $crumb): ?>
                                            <li class="breadcrumb-item <?= $index === array_key_last($breadcrumbs) ? 'active' : '' ?>"
                                                <?= $index === array_key_last($breadcrumbs) ? 'aria-current="page"' : '' ?>>
                                                <?php if ($index !== array_key_last($breadcrumbs)): ?>
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
                            <?php endif; ?>
                        </div>
                        <?php if ($pageActions): ?>
                            <div class="page-actions"><?= $pageActions ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?= $content ?>
            </div>
        </main>
    </div>

    <!-- Main JS -->
    <script src="/assets/js/app.js"></script>

    <!-- Inline script for theme initialization (before app.js runs) -->
    <script>
        (function() {
            var theme = localStorage.getItem('borjino-theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <?= $extraScripts ?>
</body>
</html>