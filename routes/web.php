<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Support\Router;
use App\Support\View;

$router = new Router();
$view = new View(dirname(__DIR__) . '/resources/views');
$homeController = new HomeController($view);

$router->get('/', [$homeController, 'index']);

return $router;
