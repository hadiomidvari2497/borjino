<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;

final class DashboardController
{
    public function __construct(private readonly View $view)
    {
    }

    public function index(): string
    {
        $user = $_SESSION['user'] ?? ['username' => 'Admin', 'is_admin' => true];
        return $this->view->render('dashboard/index', ['user' => $user]);
    }
}