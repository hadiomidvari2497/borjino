<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;

final class HomeController
{
    public function __construct(private readonly View $view)
    {
    }

    public function index(): string
    {
        return $this->view->render('home/index', [
            'title' => 'برجینو',
        ]);
    }
}
