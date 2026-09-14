<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Services\AuthService;
use App\Support\View;

final class DashboardController
{
    public function __construct(
        private View $view,
        private AuthMiddleware $middleware,
        private AuthService $auth,
    ) {
    }

    public function index(): string
    {
        $this->middleware->handle();

        return $this->view->render('dashboard/index', [
            'username' => $this->auth->userId(),
        ]);
    }
}
