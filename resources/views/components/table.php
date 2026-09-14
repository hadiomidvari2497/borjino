<?php
/**
 * Table Component
 * Usage: include 'components/table.php' with $columns, $rows, $actions, $sortable, $striped, $hover, $emptyMessage variables
 */
$columns = $columns ?? [];
$rows = $rows ?? [];
$actions = $actions ?? null;
$sortable = $sortable ?? false;
$striped = $striped ?? false;
$hover = $hover ?? true;
$emptyMessage = $emptyMessage ?? 'هیچ داده‌ای یافت نشد';
$tableClass = $tableClass ?? '';
$responsive = $responsive ?? true;
$rowHrefKey = $rowHrefKey ?? 'href';
$rowIdKey = $rowIdKey ?? 'id';
?>

<?php if ($responsive): ?>
<div class="table-responsive">
<?php endif; ?>

<table class="table data-table <?= $striped ? 'table-striped' : '' ?> <?= $hover ? 'table-hover' : '' ?> <?= htmlspecialchars($tableClass, ENT_QUOTES, 'UTF-8') ?>">
    <thead>
        <tr>
            <?php foreach ($columns as $col): ?>
                <th
                    <?= $sortable && isset($col['sort']) && $col['sort'] ? 'data-sort="true" style="cursor: pointer;"' : '' ?>
                    <?= isset($col['width']) ? 'style="width: '.htmlspecialchars($col['width'], ENT_QUOTES, 'UTF-8').'"' : '' ?>
                    scope="col">
                    <?= htmlspecialchars($col['label'], ENT_QUOTES, 'UTF-8') ?>
                    <?php if ($sortable && isset($col['sort']) && $col['sort']): ?>
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px; display: inline-block; vertical-align: middle;">
                            <polyline points="18 15 12 9 6 15"></polyline>
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    <?php endif; ?>
                </th>
            <?php endforeach; ?>
            <?php if ($actions): ?>
                <th scope="col" style="width: 120px; text-align: center;">عملیات</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rows)): ?>
            <tr>
                <td colspan="<?= count($columns) + ($actions ? 1 : 0) ?>" class="muted" style="text-align: center; padding: 3rem;">
                    <?= htmlspecialchars($emptyMessage, ENT_QUOTES, 'UTF-8') ?>
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($rows as $row): ?>
                <tr
                    <?= isset($row[$rowHrefKey]) ? 'data-href="'.htmlspecialchars($row[$rowHrefKey], ENT_QUOTES, 'UTF-8').'"' : '' ?>
                    <?= isset($row[$rowIdKey]) ? 'data-id="'.htmlspecialchars($row[$rowIdKey], ENT_QUOTES, 'UTF-8').'"' : '' ?>
                >
                    <?php foreach ($columns as $col): ?>
                        <td>
                            <?php
                            $key = $col['key'] ?? '';
                            $value = $key ? ($row[$key] ?? '') : '';
                            $type = $col['type'] ?? 'text';
                            $format = $col['format'] ?? null;

                            switch ($type) {
                                case 'badge':
                                    $badgeClass = $col['badgeClass'] ?? 'badge-secondary';
                                    if (is_callable($badgeClass)) {
                                        $badgeClass = $badgeClass($value, $row);
                                    }
                                    echo '<span class="badge '.htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8').'">'.htmlspecialchars($value, ENT_QUOTES, 'UTF-8').'</span>';
                                    break;
                                case 'date':
                                    echo $value ? htmlspecialchars(date('Y/m/d', strtotime($value)), ENT_QUOTES, 'UTF-8') : '<span class="muted">—</span>';
                                    break;
                                case 'datetime':
                                    echo $value ? htmlspecialchars(date('Y/m/d H:i', strtotime($value)), ENT_QUOTES, 'UTF-8') : '<span class="muted">—</span>';
                                    break;
                                case 'currency':
                                    echo $value ? htmlspecialchars(number_format($value, 0, '', ','), ENT_QUOTES, 'UTF-8').' ریال' : '<span class="muted">—</span>';
                                    break;
                                case 'number':
                                    echo $value !== '' ? htmlspecialchars(number_format($value), ENT_QUOTES, 'UTF-8') : '<span class="muted">—</span>';
                                    break;
                                case 'custom':
                                    if (is_callable($format)) {
                                        echo $format($value, $row);
                                    } else {
                                        echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                                    }
                                    break;
                                default:
                                    echo $value !== '' ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : '<span class="muted">—</span>';
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>
                    <?php if ($actions): ?>
                        <td style="text-align: center;">
                            <div class="action-buttons">
                                <?php foreach ($actions as $action): ?>
                                    <?php
                                    $actionHref = is_callable($action['href']) ? $action['href']($row) : $action['href'];
                                    $actionClass = $action['class'] ?? 'btn-icon-sm btn-secondary';
                                    $actionIcon = $action['icon'] ?? '';
                                    $actionLabel = $action['label'] ?? '';
                                    $actionAttrs = $action['attrs'] ?? '';
                                    $confirm = isset($action['confirm']) ? 'data-confirm="'.htmlspecialchars($action['confirm'], ENT_QUOTES, 'UTF-8').'"' : '';
                                    ?>
                                    <a class="<?= htmlspecialchars($actionClass, ENT_QUOTES, 'UTF-8') ?>"
                                       href="<?= htmlspecialchars($actionHref, ENT_QUOTES, 'UTF-8') ?>"
                                       <?= $confirm ?>
                                       <?= $actionAttrs ?>
                                       title="<?= htmlspecialchars($actionLabel, ENT_QUOTES, 'UTF-8') ?>"
                                       aria-label="<?= htmlspecialchars($actionLabel, ENT_QUOTES, 'UTF-8') ?>">
                                        <?= $actionIcon ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($responsive): ?>
</div>
<?php endif; ?>