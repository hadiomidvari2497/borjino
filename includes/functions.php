<?php
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): never { header('Location: ' . $url); exit; }
function flash(?string $message = null): ?string {
    if ($message !== null) { $_SESSION['_flash'] = $message; return null; }
    $m = $_SESSION['_flash'] ?? null; unset($_SESSION['_flash']); return $m;
}
function post(string $key, $default = '') { return $_POST[$key] ?? $default; }
function csrf_token(): string { if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(24)); return $_SESSION['_csrf']; }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="'.e(csrf_token()).'">'; }
function check_csrf(): void { if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['csrf'] ?? '')) { http_response_code(419); exit('درخواست نامعتبر است.'); } }
function money($n): string { return number_format((float)$n, 0, '.', ','); }
function jalaliToday(): string { return date('Y-m-d'); }
