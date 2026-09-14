<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Support\View;

final class ErrorController
{
    public function __construct(private readonly View $view)
    {
    }

    public function forbidden(): string
    {
        http_response_code(403);
        return $this->view->render('errors/403');
    }

    public function notFound(): string
    {
        http_response_code(404);
        return $this->view->render('errors/404');
    }

    public function serverError(): string
    {
        http_response_code(500);
        return $this->view->render('errors/500');
    }
}