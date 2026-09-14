<?php
/**
 * Modal Component
 * Usage: include 'components/modal.php' with $id, $title, $body, $footer, $size variables
 */
$id = $id ?? 'modal';
$title = $title ?? '';
$body = $body ?? '';
$footer = $footer ?? '';
$size = $size ?? ''; // sm, lg, xl
$closeBtn = $closeBtn ?? true;

$sizes = [
    'sm' => 'max-width: 360px;',
    'lg' => 'max-width: 720px;',
    'xl' => 'max-width: 960px;',
    '' => 'max-width: 560px;',
];

$style = $sizes[$size] ?? '';
?>

<div class="modal-overlay" id="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" role="dialog" aria-modal="true" aria-labelledby="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>-title" style="display: none;">
    <div class="modal" style="<?= $style ?>">
        <div class="modal-header">
            <?php if ($title): ?>
                <h3 class="modal-title" id="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>-title"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
            <?php endif; ?>
            <?php if ($closeBtn): ?>
                <button class="modal-close" data-modal-close aria-label="بستن مودال">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            <?php endif; ?>
        </div>
        <div class="modal-body">
            <?= $body ?>
        </div>
        <?php if ($footer): ?>
            <div class="modal-footer">
                <?= $footer ?>
            </div>
        <?php endif; ?>
    </div>
</div>