<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\BuildingController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Database\Connection;
use App\Middleware\AuthMiddleware;
use App\Repositories\BuildingRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Support\Router;
use App\Support\View;

$router = new Router();
$view = new View(dirname(__DIR__) . '/resources/views');
$connection = new Connection(require dirname(__DIR__) . '/config/database.php');
$pdo = $connection->getPdo();
$userRepository = new UserRepository($pdo);
$authService = new AuthService($userRepository);
$authMiddleware = new AuthMiddleware($authService);
$buildingRepository = new BuildingRepository($pdo);

$homeController = new HomeController($view);
$authController = new AuthController($view, $authService);
$dashboardController = new DashboardController($view, $authMiddleware, $authService);
$buildingController = new BuildingController($view, $buildingRepository, $authMiddleware);

$router->get('/', [$homeController, 'index']);
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->post('/logout', [$authController, 'logout']);
$router->get('/dashboard', [$dashboardController, 'index']);
$router->get('/buildings', [$buildingController, 'index']);
$router->get('/buildings/create', [$buildingController, 'create']);
$router->post('/buildings/store', [$buildingController, 'store']);
$router->get('/buildings/edit', [$buildingController, 'edit']);
$router->post('/buildings/update', [$buildingController, 'update']);
$router->post('/buildings/delete', [$buildingController, 'delete']);

return $router;
