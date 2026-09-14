<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Support\Session;
use App\Support\View;

final class AuthController
{
    public function __construct(
        private View $view,
        private AuthService $auth,
    ) {
    }

    public function showLogin(): string
    {
        if ($this->auth->check()) {
            header('Location: /dashboard', true, 302);
            return '';
        }

        return $this->view->render('auth/login', [
            'error' => Session::get('auth.error'),
        ]);
    }

    public function login(): string
    {
        $username = (string) ($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        if ($this->auth->attempt($username, $password)) {
            Session::forget('auth.error');
            header('Location: /dashboard', true, 302);
            return '';
        }

        Session::put('auth.error', 'نام کاربری یا رمز عبور نادرست است.');
        header('Location: /login', true, 302);
        return '';
    }

    public function logout(): string
    {
        $this->auth->logout();
        header('Location: /login', true, 302);
        return '';
    }
}
