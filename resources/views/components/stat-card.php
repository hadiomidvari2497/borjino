<?php
/**
 * Stat Card Component
 * Usage: include 'components/stat-card.php' with $label, $value, $icon, $iconColor, $change, $changeType, $href variables
 */
$label = $label ?? '';
$value = $value ?? '0';
$icon = $icon ?? '';
$iconColor = $iconColor ?? 'blue';
$change = $change ?? '';
$changeType = $changeType ?? '';
$href = $href ?? '';
$class = $class ?? '';

$iconSvgs = [
    'building' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="3" x2="9" y2="21"></line><line x1="15" y1="3" x2="15" y2="21"></line><line x1="3" y1="9" x2="21" y2="9"></line><line x1="3" y1="15" x2="21" y2="15"></line></svg>',
    'grid' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect></svg>',
    'home' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>',
    'credit-card' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>',
    'users' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
    'alert-triangle' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
    'bell' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>',
    'file-text' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
];
?>

<div class="stat-card <?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>" <?= $href ? 'style="cursor: pointer;" onclick="window.location.href=\''.htmlspecialchars($href, ENT_QUOTES, 'UTF-8').'\'"' : '' ?>>
    <?php if ($icon && isset($iconSvgs[$icon])): ?>
        <div class="stat-icon <?= htmlspecialchars($iconColor, ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true">
            <?= $iconSvgs[$icon] ?>
        </div>
    <?php elseif ($icon): ?>
        <div class="stat-icon <?= htmlspecialchars($iconColor, ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true">
            <?= $icon ?>
        </div>
    <?php endif; ?>
    <div class="stat-label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></div>
    <div class="stat-value"><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></div>
    <?php if ($change): ?>
        <div class="stat-change <?= $changeType === 'positive' ? 'positive' : ($changeType === 'negative' ? 'negative' : '') ?>">
            <?php if ($changeType === 'positive'): ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polyline points="23 6 13.5 15.5 8.5 10.5"></polyline>
                    <polyline points="17 6 23 6 23 12"></polyline>
                </svg>
            <?php elseif ($changeType === 'negative'): ?>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <polyline points="23 18 13.5 8.5 8.5 13.5"></polyline>
                    <polyline points="17 18 23 18 23 12"></polyline>
                </svg>
            <?php endif; ?>
            <?= htmlspecialchars($change, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>
</div>