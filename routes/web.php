<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\BlockController;
use App\Controllers\BuildingController;
use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Controllers\UnitController;
use App\Database\Connection;
use App\Middleware\AuthMiddleware;
use App\Repositories\BlockRepository;
use App\Repositories\BuildingRepository;
use App\Repositories\UnitRepository;
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
$blockRepository = new BlockRepository($pdo);
$unitRepository = new UnitRepository($pdo);

$homeController = new HomeController($view);
$authController = new AuthController($view, $authService);
$dashboardController = new DashboardController($view, $authMiddleware, $authService);
$buildingController = new BuildingController($view, $buildingRepository, $authMiddleware);
$blockController = new BlockController($view, $blockRepository, $buildingRepository, $authMiddleware);
$unitController = new UnitController($view, $unitRepository, $buildingRepository, $blockRepository, $authMiddleware);

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
$router->get('/blocks', [$blockController, 'index']);
$router->get('/blocks/create', [$blockController, 'create']);
$router->post('/blocks/store', [$blockController, 'store']);
$router->get('/blocks/edit', [$blockController, 'edit']);
$router->post('/blocks/update', [$blockController, 'update']);
$router->post('/blocks/delete', [$blockController, 'delete']);
$router->get('/units', [$unitController, 'index']);
$router->get('/units/create', [$unitController, 'create']);
$router->post('/units/store', [$unitController, 'store']);
$router->get('/units/edit', [$unitController, 'edit']);
$router->post('/units/update', [$unitController, 'update']);
$router->post('/units/delete', [$unitController, 'delete']);
$router->get('/units/generate', [$unitController, 'generate']);
$router->post('/units/generate', [$unitController, 'generateStore']);

return $router;
