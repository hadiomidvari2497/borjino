<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Database\Connection;
use App\Middleware\AuthMiddleware;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Support\Router;
use App\Support\View;

$router = new Router();
$view = new View(dirname(__DIR__) . '/resources/views');
$connection = new Connection(require dirname(__DIR__) . '/config/database.php');
$userRepository = new UserRepository($connection->getPdo());
$authService = new AuthService($userRepository);
$authMiddleware = new AuthMiddleware($authService);

$homeController = new HomeController($view);
$authController = new AuthController($view, $authService);
$dashboardController = new DashboardController($view, $authMiddleware, $authService);

$router->get('/', [$homeController, 'index']);
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->post('/logout', [$authController, 'logout']);
$router->get('/dashboard', [$dashboardController, 'index']);

return $router;
