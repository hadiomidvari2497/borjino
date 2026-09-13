<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Support\Router;
use App\Support\View;

session_start();

$router = new Router();
$view = new View(dirname(__DIR__) . '/resources/views');
$homeController = new HomeController($view);
$authController = new AuthController($view);
$dashboardController = new DashboardController($view);

$router->get('/', [$homeController, 'index']);
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->get('/logout', [$authController, 'logout']);
$router->get('/dashboard', [$dashboardController, 'index']);

return $router;