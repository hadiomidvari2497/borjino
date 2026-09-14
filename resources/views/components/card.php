<?php
/**
 * Card Component
 * Usage: include 'components/card.php' with $title, $content, $actions, $icon, $iconColor variables
 */
$title = $title ?? '';
$content = $content ?? '';
$actions = $actions ?? '';
$icon = $icon ?? '';
$iconColor = $iconColor ?? 'blue';
$class = $class ?? '';
$header = $header ?? true;
$footer = $footer ?? false;
$bodyClass = $bodyClass ?? '';
?>

<div class="card <?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($header && ($title || $actions || $icon)): ?>
        <div class="card-header">
            <?php if ($icon): ?>
                <div class="stat-icon <?= htmlspecialchars($iconColor, ENT_QUOTES, 'UTF-8') ?>" aria-hidden="true">
                    <?= $icon ?>
                </div>
            <?php endif; ?>
            <?php if ($title): ?>
                <h3 class="card-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
            <?php endif; ?>
            <?php if ($actions): ?>
                <div class="card-actions"><?= $actions ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div class="card-body <?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
        <?= $content ?>
    </div>
    <?php if ($footer): ?>
        <div class="card-footer"><?= $footer ?></div>
    <?php endif; ?>
</div>