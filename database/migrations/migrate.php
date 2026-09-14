<?php

declare(strict_types=1);

/**
 * Database Migration Runner for Borjino
 * Runs all migration files in database/migrations/ directory
 */

require_once __DIR__ . '/../../bootstrap/autoload.php';

use App\Database\Connection;

$pdo = Connection::make();

$migrationsDir = __DIR__ . '/migrations';

if (!is_dir($migrationsDir)) {
    echo "Migrations directory not found: $migrationsDir\n";
    exit(1);
}

$files = glob($migrationsDir . '/*.php');
sort($files);

if (empty($files)) {
    echo "No migration files found.\n";
    exit(0);
}

echo "Running " . count($files) . " migration(s)...\n\n";

foreach ($files as $file) {
    $filename = basename($file);
    echo "Running: $filename ... ";
    
    try {
        $pdo->beginTransaction();
        require $file;
        $pdo->commit();
        echo "OK\n";
    } catch (Throwable $e) {
        $pdo->rollBack();
        echo "FAILED\n";
        echo "Error: " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "\nAll migrations completed successfully!\n";