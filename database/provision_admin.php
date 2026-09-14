<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config/database.php';
$password = getenv('ADMIN_PASSWORD');
if ($password === false || strlen($password) < 12) {
    fwrite(STDERR, "ADMIN_PASSWORD must be provided and contain at least 12 characters." . PHP_EOL);
    exit(1);
}

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $config['host'], $config['port'], $config['database'], $config['charset']
);

$pdo = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

$group = $pdo->query("SELECT id FROM permission_groups WHERE name = 'administrators' LIMIT 1")->fetch();
if (!$group) {
    fwrite(STDERR, "The administrators group is missing. Run migrations first." . PHP_EOL);
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$pdo->beginTransaction();
try {
    $user = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
    $user->execute(['username' => 'admin']);
    $existing = $user->fetch();

    if ($existing) {
        $statement = $pdo->prepare(
            'UPDATE users SET password_hash = :password_hash, is_active = 1, is_system_admin = 1 WHERE id = :id'
        );
        $statement->execute(['password_hash' => $hash, 'id' => (int) $existing['id']]);
        $userId = (int) $existing['id'];
    } else {
        $statement = $pdo->prepare(
            'INSERT INTO users (username, password_hash, is_active, is_system_admin)
             VALUES (:username, :password_hash, 1, 1)'
        );
        $statement->execute(['username' => 'admin', 'password_hash' => $hash]);
        $userId = (int) $pdo->lastInsertId();
    }

    $membership = $pdo->prepare(
        'INSERT IGNORE INTO user_groups (user_id, group_id) VALUES (:user_id, :group_id)'
    );
    $membership->execute(['user_id' => $userId, 'group_id' => (int) $group['id']]);

    $pdo->commit();
    echo "Admin user provisioned successfully." . PHP_EOL;
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    fwrite(STDERR, $exception->getMessage() . PHP_EOL);
    exit(1);
}
