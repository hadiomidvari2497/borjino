<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/config/database.php';

$dsn = sprintf(
    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
    $config['host'], $config['port'], $config['database'], $config['charset']
);

$pdo = new PDO($dsn, $config['username'], $config['password'], [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

$pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_schema_migrations_migration (migration)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$directory = __DIR__ . '/migrations';
$files = glob($directory . '/*.sql') ?: [];
sort($files, SORT_STRING);

$applied = $pdo->query('SELECT migration FROM schema_migrations')->fetchAll(PDO::FETCH_COLUMN);
$applied = array_fill_keys($applied, true);

foreach ($files as $file) {
    $name = basename($file);
    if (isset($applied[$name])) {
        continue;
    }

    $sql = trim((string) file_get_contents($file));
    if ($sql === '') {
        continue;
    }

    try {
        // MySQL DDL statements implicitly commit transactions, so migrations
        // are intentionally executed without wrapping them in a transaction.
        $pdo->exec($sql);
        $statement = $pdo->prepare('INSERT INTO schema_migrations (migration) VALUES (:migration)');
        $statement->execute(['migration' => $name]);
        echo "Applied: {$name}" . PHP_EOL;
    } catch (Throwable $exception) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        fwrite(STDERR, "Migration failed: {$name}" . PHP_EOL . $exception->getMessage() . PHP_EOL);
        exit(1);
    }
}
