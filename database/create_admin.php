<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config/database.php';
$password = getenv('ADMIN_PASSWORD');

if (!is_string($password) || strlen($password) < 12) {
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

$groupId = $pdo->query("SELECT id FROM permission_groups WHERE name = 'administrators' LIMIT 1")->fetchColumn();
if ($groupId === false) {
    throw new RuntimeException('administrators group is missing. Run migrations first.');
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$pdo->beginTransaction();
try {
    $statement = $pdo->prepare(
        'INSERT INTO users (username, password_hash, is_active, is_system_admin)
         VALUES (\'admin\', :password_hash, 1, 1)
         ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash), is_active = 1, is_system_admin = 1'
    );
    $statement->execute(['password_hash' => $hash]);

    $userId = (int) $pdo->query("SELECT id FROM users WHERE username = 'admin' LIMIT 1")->fetchColumn();
    $statement = $pdo->prepare('INSERT IGNORE INTO user_groups (user_id, group_id) VALUES (:user_id, :group_id)');
    $statement->execute(['user_id' => $userId, 'group_id' => (int) $groupId]);

    $pdo->commit();
    echo "Admin user provisioned successfully." . PHP_EOL;
} catch (Throwable $exception) {
    $pdo->rollBack();
    throw $exception;
}
