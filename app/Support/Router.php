<?php

declare(strict_types=1);

namespace App\Support;

use RuntimeException;

final class Router
{
    /** @var array<string, array{handler: callable, params: array<int, string>, types: array<int, string>}> */
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function patch(string $path, callable $handler): void
    {
        $this->addRoute('PATCH', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $pattern = $this->convertPathToPattern($path);
        $params = $this->extractParams($path);
        $types = $this->extractTypes($path);
        $this->routes[$method . ' ' . $pattern] = ['handler' => $handler, 'params' => $params, 'types' => $types];
    }

    private function convertPathToPattern(string $path): string
    {
        $escaped = preg_quote($path, '#');
        $pattern = preg_replace('/\\\{([^}:]+)(:[^}]+)?\\\}/', '([^/]+)', $escaped);
        return $pattern;
    }

    private function extractParams(string $path): array
    {
        preg_match_all('/\{([^}:]+)(:[^}]+)?\}/', $path, $matches);
        return $matches[1] ?? [];
    }

    private function extractTypes(string $path): array
    {
        preg_match_all('/\{[^}:]+:([^}]+)\}/', $path, $matches);
        $types = [];
        foreach ($matches[1] ?? [] as $type) {
            $types[] = $type;
        }
        return $types;
    }

    public function dispatch(string $method, string $path): mixed
    {
        $method = strtoupper($method);

        foreach ($this->routes as $routeKey => $route) {
            $parts = explode(' ', $routeKey, 2);
            $routeMethod = $parts[0] ?? '';
            $routePattern = $parts[1] ?? '';

            if ($routeMethod !== $method) {
                continue;
            }

            $pattern = '#^' . $routePattern . '$#';
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $args = [];
                foreach ($matches as $i => $value) {
                    $type = $route['types'][$i] ?? 'string';
                    $args[] = $this->castValue($value, $type);
                }
                return $route['handler'](...$args);
            }
        }

        throw new RuntimeException('Route not found.', 404);
    }

    private function castValue(string $value, string $type): mixed
    {
        return match ($type) {
            'int', 'integer', '\d+' => (int) $value,
            'float', 'double' => (float) $value,
            'bool', 'boolean' => (bool) $value,
            default => $value,
        };
    }
}