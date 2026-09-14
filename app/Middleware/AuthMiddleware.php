<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Services\AuthService;
use RuntimeException;

final class AuthMiddleware
{
    public function __construct(private AuthService $auth)
    {
    }

    public function handle(): void
    {
        if (!$this->auth->check()) {
            header('Location: /login', true, 302);
            exit;
        }
    }

    public function requirePermission(string $resource, string $action): void
    {
        $this->handle();
        if (!$this->auth->can($resource, $action)) {
            throw new RuntimeException('Forbidden.', 403);
        }
    }
}
