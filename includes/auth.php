<?php
function require_login(): void { if (empty($_SESSION['user'])) redirect('login.php'); }
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function login_user(array $user): void { session_regenerate_id(true); $_SESSION['user'] = ['id'=>$user['id'], 'username'=>$user['username'], 'full_name'=>$user['full_name'] ?? '']; }
function logout_user(): void { $_SESSION = []; if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(), '', time()-42000, $p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy(); }
