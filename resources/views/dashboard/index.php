<?php
/**
 * Dashboard View
 */
$title = 'داشبورد';
$breadcrumbs = [];
$currentRoute = '/dashboard';

// Mock stats data
$stats = [
    [
        'label' => 'تعداد ساختمان‌ها',
        'value' => '۱۲',
        'icon' => 'building',
        'iconColor' => 'blue',
        'change' => '+۲ از ماه گذشته',
        'changeType' => 'positive',
        'href' => '/buildings'
    ],
    [
        'label' => 'تعداد بلوک‌ها',
        'value' => '۴۸',
        'icon' => 'grid',
        'iconColor' => 'green',
        'change' => '+۵ از ماه گذشته',
        'changeType' => 'positive',
        'href' => '/blocks'
    ],
    [
        'label' => 'تعداد واحدها',
        'value' => '۳۵۶',
        'icon' => 'home',
        'iconColor' => 'purple',
        'change' => '+۱۲ از ماه گذشته',
        'changeType' => 'positive',
        'href' => '/units'
    ],
    [
        'label' => 'واحدهای خالی',
        'value' => '۲۳',
        'icon' => 'home',
        'iconColor' => 'orange',
        'change' => '-۳ از ماه گذشته',
        'changeType' => 'positive',
        'href' => '/units?status=vacant'
    ],
    [
        'label' => 'واحدهای بدهکار',
        'value' => '۴۱',
        'icon' => 'alert-triangle',
        'iconColor' => 'red',
        'change' => '+۷ از ماه گذشته',
        'changeType' => 'negative',
        'href' => '/units?financial=debtor'
    ],
    [
        'label' => 'مجموع بدهی‌ها',
        'value' => '۱,۲۳۴,۵۶۷,۸۹۰ ریال',
        'icon' => 'credit-card',
        'iconColor' => 'red',
        'change' => '+۱۲٪ از ماه گذشته',
        'changeType' => 'negative',
        'href' => '/charges'
    ],
    [
        'label' => 'پرداخت‌های امروز',
        'value' => '۱۲',
        'icon' => 'file-text',
        'iconColor' => 'green',
        'change' => 'مبلغ: ۴۵,۶۷۰,۰۰۰ ریال',
        'changeType' => 'positive',
        'href' => '/payments?date=today'
    ],
    [
        'label' => 'اعلان‌های جدید',
        'value' => '۵',
        'icon' => 'bell',
        'iconColor' => 'cyan',
        'change' => '۳ خوانده نشده',
        'changeType' => '',
        'href' => '/notifications'
    ],
];

// Build stat cards HTML
$statCardsHtml = '';
foreach ($stats as $stat) {
    ob_start();
    $label = $stat['label'];
    $value = $stat['value'];
    $icon = $stat['icon'];
    $iconColor = $stat['iconColor'];
    $change = $stat['change'];
    $changeType = $stat['changeType'];
    $href = $stat['href'];
    include __DIR__ . '/../components/stat-card.php';
    $statCardsHtml .= ob_get_clean();
}

// Recent activities mock data
$activities = [
    ['user' => 'احمد محمدی', 'action' => 'ثبت شارژ جدید برای واحد ۱۰۱', 'time' => '۱۰ دقیقه پیش', 'status' => 'success'],
    ['user' => 'فاطمه احمدی', 'action' => 'تایید پرداخت قرارداد ۴۵', 'time' => '۲۵ دقیقه پیش', 'status' => 'success'],
    ['user' => 'سیستم', 'action' => 'صدور خودکار شارژ ماهانه', 'time' => '۱ ساعت پیش', 'status' => 'info'],
    ['user' => 'رضا کریمی', 'action' => 'افزودن ساختمان جدید "پارسیان"', 'time' => '۳ ساعت پیش', 'status' => 'success'],
    ['user' => 'مریم حسینی', 'action' => 'ثبت تعمیرات برای بلوک ب', 'time' => '۵ ساعت پیش', 'status' => 'warning'],
    ['user' => 'سیستم', 'action' => 'پشتیبان‌گیری خودکار دیتابیس', 'time' => 'دیروز ۰۲:۰۰', 'status' => 'info'],
];

// Notifications mock data
$notifications = [
    ['title' => 'شارژ جدید صادر شد', 'message' => 'شارژ ماه خرداد برای ساختمان سپهر صادر گردید', 'time' => '۱۵ دقیقه پیش', 'type' => 'info'],
    ['title' => 'پرداخت تایید شد', 'message' => 'پرداخت ۱۲,۵۰۰,۰۰۰ ریال از واحد ۲۰۵ تایید گردید', 'time' => '۱ ساعت پیش', 'type' => 'success'],
    ['title' => 'هشدار بدهی', 'message' => 'واحد ۳۰۱ بلوک ب بیش از ۳ ماه بدهکار است', 'time' => '۳ ساعت پیش', 'type' => 'warning'],
    ['title' => 'تعمیرات برنامه‌ریزی', 'message' => 'تعمیرات آسانسور بلوک الف روز شنبه انجام می‌شود', 'time' => 'دیروز', 'type' => 'info'],
];

$pageActions = '';
?>

<div class="grid grid-4" style="margin-bottom: 1.5rem;">
    <?= $statCardsHtml ?>
</div>

<div class="grid grid-2" style="margin-bottom: 1.5rem;">
    <!-- Recent Activities -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">آخرین فعالیت‌ها</h3>
            <a href="/activities" class="btn btn-sm btn-secondary">مشاهده همه</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="margin: 0;">
                    <thead>
                        <tr>
                            <th style="width: 30%;">کاربر</th>
                            <th>عملیات</th>
                            <th style="width: 15%;">زمان</th>
                            <th style="width: 15%;">وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: 500;"><?= htmlspecialchars($activity['user'], ENT_QUOTES, 'UTF-8') ?></div>
                                </td>
                                <td><?= htmlspecialchars($activity['action'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="muted" style="font-size: 0.8125rem;"><?= htmlspecialchars($activity['time'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <?php
                                    $statusBadge = [
                                        'success' => 'badge-success',
                                        'warning' => 'badge-warning',
                                        'info' => 'badge-info',
                                        'danger' => 'badge-danger',
                                    ];
                                    $badge = $statusBadge[$activity['status']] ?? 'badge-secondary';
                                    $statusLabel = [
                                        'success' => 'موفق',
                                        'warning' => 'در انتظار',
                                        'info' => 'اطلاعاتی',
                                        'danger' => 'خطا',
                                    ];
                                    $label = $statusLabel[$activity['status']] ?? 'نامشخص';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= $label ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">اعلان‌ها</h3>
            <a href="/notifications" class="btn btn-sm btn-secondary">مشاهده همه</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <div style="display: flex; flex-direction: column;">
                <?php foreach ($notifications as $notif): ?>
                    <div class="alert alert-<?= $notif['type'] ?>" style="margin: 0; border-radius: 0; border-left: 3px solid var(--<?= $notif['type'] === 'success' ? 'success' : ($notif['type'] === 'warning' ? 'warning' : ($notif['type'] === 'danger' ? 'danger' : 'primary')) ?>); border-right: none; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
                        <div style="flex: 1;">
                            <div class="alert-title"><?= htmlspecialchars($notif['title'], ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="alert-message" style="margin-bottom: 0.25rem;"><?= htmlspecialchars($notif['message'], ENT_QUOTES, 'UTF-8') ?></div>
                            <div style="font-size: 0.7rem; color: var(--text-light);"><?= htmlspecialchars($notif['time'], ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">دسترسی سریع</h3>
    </div>
    <div class="card-body">
        <div class="grid grid-4" style="gap: 1rem;">
            <a href="/buildings/create" class="card" style="padding: 1.5rem; text-align: center; text-decoration: none; transition: all var(--transition);">
                <div class="stat-icon blue" style="margin: 0 auto 1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="9" y1="3" x2="9" y2="21"></line>
                        <line x1="15" y1="3" x2="15" y2="21"></line>
                        <line x1="3" y1="9" x2="21" y2="9"></line>
                        <line x1="3" y1="15" x2="21" y2="15"></line>
                    </svg>
                </div>
                <div style="font-weight: 600; color: var(--text);">افزودن ساختمان</div>
                <div class="muted" style="font-size: 0.75rem; margin-top: 0.25rem;">ثبت ساختمان جدید</div>
            </a>
            <a href="/units/create" class="card" style="padding: 1.5rem; text-align: center; text-decoration: none; transition: all var(--transition);">
                <div class="stat-icon green" style="margin: 0 auto 1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </div>
                <div style="font-weight: 600; color: var(--text);">افزودن واحد</div>
                <div class="muted" style="font-size: 0.75rem; margin-top: 0.25rem;">ثبت واحد جدید</div>
            </a>
            <a href="/charges/create" class="card" style="padding: 1.5rem; text-align: center; text-decoration: none; transition: all var(--transition);">
                <div class="stat-icon orange" style="margin: 0 auto 1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
                </div>
                <div style="font-weight: 600; color: var(--text);">صدور شارژ</div>
                <div class="muted" style="font-size: 0.75rem; margin-top: 0.25rem;">محاسبه و صدور شارژ</div>
            </a>
            <a href="/reports" class="card" style="padding: 1.5rem; text-align: center; text-decoration: none; transition: all var(--transition);">
                <div class="stat-icon cyan" style="margin: 0 auto 1rem;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
                <div style="font-weight: 600; color: var(--text);">گزارش‌ها</div>
                <div class="muted" style="font-size: 0.75rem; margin-top: 0.25rem;">مشاهده گزارش‌ها</div>
            </a>
        </div>
    </div>
</div>