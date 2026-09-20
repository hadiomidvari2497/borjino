<?php
declare(strict_types=1);

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$root = dirname(__DIR__);

/*
 * Let GitHub Pages/static assets pass through, but intercept PHP page requests
 * so the preview session below is available before the application executes.
 */
$isPhpRequest = str_ends_with(strtolower($uri), '.php');
if ($uri !== '/' && !$isPhpRequest && is_file($root . $uri)) {
    return false;
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_save_path(sys_get_temp_dir());
    session_start();
}

$_SESSION['user'] = [
    'id' => 1,
    'username' => 'admin',
    'full_name' => 'مدیر سیستم',
    'access_group_id' => 1,
];

$path = trim($uri, '/');
if ($path === '') {
    $path = 'index.php';
} elseif (str_ends_with($path, '.html')) {
    $path = substr($path, 0, -5) . '.php';
} elseif (!str_ends_with($path, '.php')) {
    $path .= '.php';
}

$target = $root . '/' . $path;
if (!is_file($target) || !str_ends_with($target, '.php')) {
    http_response_code(404);
    echo '<!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><title>404</title><body style="font-family:sans-serif;padding:40px">صفحه پیدا نشد.</body></html>';
    return true;
}

ob_start();
try {
    require $target;
    $html = ob_get_clean();
} catch (Throwable $e) {
    ob_end_clean();
    http_response_code(500);
    echo '<!doctype html><html lang="fa" dir="rtl"><meta charset="utf-8"><title>Preview Error</title><body style="font-family:sans-serif;padding:40px"><h2>خطا در رندر Preview</h2><pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre></body></html>';
    return true;
}

$html = preg_replace_callback(
    '/\b(href|action)=(["\'])([^"\']+?)(?:\.php)([^"\']*)\2/i',
    static function (array $m): string {
        return $m[1] . '=' . $m[2] . $m[3] . '.html' . $m[4] . $m[2];
    },
    $html
);

echo $html;
