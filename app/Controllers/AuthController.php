<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;

final class AuthController
{
    public function __construct(private readonly View $view)
    {
    }

    public function showLogin(array $data = []): string
    {
        return $this->view->render('auth/login', $data);
    }

    public function login(): string
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            return $this->view->render('auth/login', [
                'error' => 'نام کاربری و رمز عبور الزامی است.',
                'old' => ['username' => $username],
            ]);
        }

        // TODO: Implement actual authentication against database
        // For now, demo credentials: admin / admin123
        if ($username === 'admin' && $password === 'admin123') {
            // Set session (simplified)
            $_SESSION['user'] = [
                'username' => 'admin',
                'is_admin' => true,
            ];
            header('Location: /dashboard');
            exit;
        }

        return $this->view->render('auth/login', [
            'error' => 'نام کاربری یا رمز عبور اشتباه است.',
            'old' => ['username' => $username],
        ]);
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}