<?php

declare(strict_types=1);

use App\Support\Router;

require_once dirname(__DIR__) . '/bootstrap/autoload.php';

/** @var Router $router */
$router = require dirname(__DIR__) . '/routes/web.php';

try {
    echo $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/');
} catch (Throwable $exception) {
    http_response_code((int) ($exception->getCode() >= 400 ? $exception->getCode() : 500));
    echo 'Application error.';
}
