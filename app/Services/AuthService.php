<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Support\Session;

final class AuthService
{
    public function __construct(private UserRepository $users)
    {
    }

    public function attempt(string $username, string $password): bool
    {
        $username = trim($username);
        if ($username === '' || $password === '') {
            return false;
        }

        $user = $this->users->findActiveByUsername($username);
        if ($user === null || !password_verify($password, (string) $user['password_hash'])) {
            return false;
        }

        if (password_needs_rehash((string) $user['password_hash'], PASSWORD_DEFAULT)) {
            // Rehashing is intentionally left to the user-management flow so this
            // authentication step remains read-only apart from last-login metadata.
        }

        Session::regenerate();
        Session::put('auth.user_id', (int) $user['id']);
        Session::put('auth.username', (string) $user['username']);
        Session::put('auth.is_system_admin', (bool) $user['is_system_admin']);
        $this->users->updateLastLogin((int) $user['id']);

        return true;
    }

    public function check(): bool
    {
        return Session::get('auth.user_id') !== null;
    }

    public function userId(): ?int
    {
        $id = Session::get('auth.user_id');
        return $id === null ? null : (int) $id;
    }

    public function logout(): void
    {
        Session::destroy();
    }

    public function can(string $resource, string $action): bool
    {
        $userId = $this->userId();
        if ($userId === null) {
            return false;
        }

        if ((bool) Session::get('auth.is_system_admin', false)) {
            return true;
        }

        return $this->users->hasPermission($userId, $resource, $action);
    }
}
