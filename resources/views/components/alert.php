<?php
/**
 * Alert Component
 * Usage: include 'components/alert.php' with $type, $title, $message, $dismissible variables
 */
$type = $type ?? 'info';
$title = $title ?? '';
$message = $message ?? '';
$dismissible = $dismissible ?? true;
$class = $class ?? '';
$icon = $icon ?? '';

$icons = [
    'primary' => '<svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
    'success' => '<svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>',
    'warning' => '<svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
    'danger' => '<svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>',
    'info' => '<svg class="alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
];

$iconHtml = $icon ?: ($icons[$type] ?? $icons['info']);
?>

<div class="alert alert-<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>" role="alert">
    <?= $iconHtml ?>
    <div class="alert-content">
        <?php if ($title): ?>
            <div class="alert-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if ($message): ?>
            <div class="alert-message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
    </div>
    <?php if ($dismissible): ?>
        <button class="alert-dismiss" aria-label="بستن هشدار">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    <?php endif; ?>
</div>