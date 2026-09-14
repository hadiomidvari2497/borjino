<?php
/**
 * Badge Component
 * Usage: include 'components/badge.php' with $label, $variant, $class variables
 */
$label = $label ?? '';
$variant = $variant ?? 'secondary';
$class = $class ?? '';
$dot = $dot ?? false;
$dotColor = $dotColor ?? '';

$variants = [
    'primary' => 'badge-primary',
    'success' => 'badge-success',
    'warning' => 'badge-warning',
    'danger' => 'badge-danger',
    'info' => 'badge-info',
    'secondary' => 'badge-secondary',
];

$variantClass = $variants[$variant] ?? $variants['secondary'];
?>

<span class="badge <?= htmlspecialchars($variantClass, ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($class, ENT_QUOTES, 'UTF-8') ?>">
    <?php if ($dot): ?>
        <span style="width: 8px; height: 8px; border-radius: 50%; background: <?= htmlspecialchars($dotColor ?: 'currentColor', ENT_QUOTES, 'UTF-8') ?>; margin-left: 6px; display: inline-block;"></span>
    <?php endif; ?>
    <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>
</span>