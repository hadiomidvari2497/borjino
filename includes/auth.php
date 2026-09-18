<?php
function require_login(): void {
    if (empty($_SESSION['user'])) redirect('login.php');
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function login_user(array $user): void {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id'=>(int)$user['id'],
        'username'=>$user['username'],
        'full_name'=>$user['full_name'] ?? '',
        'access_group_id'=>isset($user['access_group_id']) ? (int)$user['access_group_id'] : null
    ];
}

function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p=session_get_cookie_params();
        setcookie(session_name(), '', time()-42000, $p['path'],$p['domain'],$p['secure'],$p['httponly']);
    }
    session_destroy();
}

function has_permission(string $resource, string $action='view'): bool {
    $user=current_user();
    if (!$user) return false;
    if (($user['username'] ?? '') === 'admin') return true;
    $groupId=(int)($user['access_group_id'] ?? 0);
    if (!$groupId) return false;
    $allowed=['view'=>'can_view','create'=>'can_create','edit'=>'can_edit','delete'=>'can_delete'];
    if (!isset($allowed[$action])) return false;
    global $pdo;
    $st=$pdo->prepare("SELECT {$allowed[$action]} FROM permissions WHERE group_id=? AND resource=? LIMIT 1");
    $st->execute([$groupId,$resource]);
    return (bool)$st->fetchColumn();
}

function require_permission(string $resource, string $action='view'): void {
    require_login();
    if (!has_permission($resource,$action)) {
        http_response_code(403);
        exit('شما اجازه انجام این عملیات را ندارید.');
    }
}

function log_activity(string $action, ?string $resource=null, ?int $resourceId=null, ?string $details=null): void {
    global $pdo;
    $user=current_user();
    $st=$pdo->prepare('INSERT INTO activity_log(user_id,action,resource,resource_id,details,ip_address) VALUES(?,?,?,?,?,?)');
    $st->execute([
        $user['id'] ?? null,
        $action,
        $resource,
        $resourceId,
        $details,
        $_SERVER['REMOTE_ADDR'] ?? null
    ]);
}
