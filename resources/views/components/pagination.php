<?php
/**
 * Pagination Component
 * Usage: include 'components/pagination.php' with $currentPage, $totalPages, $baseUrl, $queryParams variables
 */
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$baseUrl = $baseUrl ?? '';
$queryParams = $queryParams ?? [];

function paginationUrl($page, $baseUrl, $queryParams) {
    $params = $queryParams;
    $params['page'] = $page;
    $query = http_build_query($params);
    return $baseUrl . ($query ? '?' . $query : '');
}

if ($totalPages <= 1) {
    return;
}
?>

<nav class="pagination" aria-label="صفحه‌بندی">
    <?php
    $prevPage = max(1, $currentPage - 1);
    $nextPage = min($totalPages, $currentPage + 1);
    ?>
    <a class="page-link" href="<?= htmlspecialchars(paginationUrl($prevPage, $baseUrl, $queryParams), ENT_QUOTES, 'UTF-8') ?>" <?= $currentPage <= 1 ? 'aria-disabled="true" tabindex="-1"' : '' ?> rel="prev">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"></polyline>
        </svg>
    </a>

    <?php
    $range = 2;
    $start = max(1, $currentPage - $range);
    $end = min($totalPages, $currentPage + $range);

    if ($start > 1) {
        echo '<a class="page-link" href="'.htmlspecialchars(paginationUrl(1, $baseUrl, $queryParams), ENT_QUOTES, 'UTF-8').'">1</a>';
        if ($start > 2) {
            echo '<span class="page-link" aria-hidden="true">…</span>';
        }
    }

    for ($i = $start; $i <= $end; $i++):
    ?>
        <a class="page-link <?= $i === $currentPage ? 'active' : '' ?>"
           href="<?= htmlspecialchars(paginationUrl($i, $baseUrl, $queryParams), ENT_QUOTES, 'UTF-8') ?>"
           <?= $i === $currentPage ? 'aria-current="page"' : '' ?>>
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($end < $totalPages): ?>
        <?php if ($end < $totalPages - 1): ?>
            <span class="page-link" aria-hidden="true">…</span>
        <?php endif; ?>
        <a class="page-link" href="<?= htmlspecialchars(paginationUrl($totalPages, $baseUrl, $queryParams), ENT_QUOTES, 'UTF-8') ?>">
            <?= $totalPages ?>
        </a>
    <?php endif; ?>

    <a class="page-link" href="<?= htmlspecialchars(paginationUrl($nextPage, $baseUrl, $queryParams), ENT_QUOTES, 'UTF-8') ?>" <?= $currentPage >= $totalPages ? 'aria-disabled="true" tabindex="-1"' : '' ?> rel="next">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"></polyline>
        </svg>
    </a>

    <span class="page-info">
        صفحه <?= $currentPage ?> از <?= $totalPages ?>
    </span>
</nav>