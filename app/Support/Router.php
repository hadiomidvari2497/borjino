<?php

declare(strict_types=1);

namespace App\Support;

use RuntimeException;

final class Router
{
    /** @var array<int, array{method: string, pattern: string, handler: callable, types: array<int, string>}> */
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
        [$pattern, $types] = $this->compilePath($path);
        $this->routes[] = [
            'method' => strtoupper($method),
            'pattern' => $pattern,
            'handler' => $handler,
            'types' => $types,
        ];
    }

    /** @return array{0: string, 1: array<int, string>} */
    private function compilePath(string $path): array
    {
        $regex = '#^';
        $types = [];
        $offset = 0;

        preg_match_all('/\{([^}:]+)(?::([^}]+))?\}/', $path, $matches, PREG_OFFSET_CAPTURE);
        foreach ($matches[0] ?? [] as $index => $match) {
            $placeholder = (string) $match[0];
            $position = (int) $match[1];
            $regex .= preg_quote(substr($path, $offset, $position - $offset), '#');

            $constraint = $matches[2][$index][0] ?? null;
            $regex .= '(' . ($constraint !== null && $constraint !== '' ? $constraint : '[^/]+') . ')';
            $types[] = $this->typeForConstraint($constraint);
            $offset = $position + strlen($placeholder);
        }

        $regex .= preg_quote(substr($path, $offset), '#') . '$#';

        return [$regex, $types];
    }

    private function typeForConstraint(?string $constraint): string
    {
        return match ($constraint) {
            '\\d+', 'int', 'integer' => 'int',
            'float', 'double' => 'float',
            'bool', 'boolean' => 'bool',
            default => 'string',
        };
    }

    public function dispatch(string $method, string $path): mixed
    {
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches) !== 1) {
                continue;
            }

            array_shift($matches);
            $args = [];
            foreach ($matches as $index => $value) {
                $args[] = $this->castValue((string) $value, $route['types'][$index] ?? 'string');
            }

            return ($route['handler'])(...$args);
        }

        throw new RuntimeException('Route not found.', 404);
    }

    private function castValue(string $value, string $type): mixed
    {
        return match ($type) {
            'int' => (int) $value,
            'float' => (float) $value,
            'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            default => $value,
        };
    }
}
