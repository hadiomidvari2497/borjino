<?php

declare(strict_types=1);

namespace App\Support;

use RuntimeException;

final class Router
{
    /** @var array<string, callable> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET ' . $path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST ' . $path] = $handler;
    }

    public function dispatch(string $method, string $path): mixed
    {
        $key = strtoupper($method) . ' ' . $path;
        $handler = $this->routes[$key] ?? null;

        if ($handler === null) {
            throw new RuntimeException('Route not found.', 404);
        }

        return $handler();
    }
}
