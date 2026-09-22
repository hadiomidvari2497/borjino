<?php

declare(strict_types=1);

use App\Controllers\AuthController;
<<<<<<< ours
=======
use App\Controllers\BlockController;
>>>>>>> theirs
use App\Controllers\BuildingController;
use App\Controllers\DashboardController;
use App\Controllers\ErrorController;
use App\Controllers\HomeController;
<<<<<<< ours
use App\Database\Connection;
use App\Middleware\AuthMiddleware;
use App\Repositories\BuildingRepository;
use App\Repositories\UserRepository;
use App\Services\AuthService;
=======
use App\Controllers\UnitController;
>>>>>>> theirs
use App\Support\Router;
use App\Support\View;

$router = new Router();
$view = new View(dirname(__DIR__) . '/resources/views');
<<<<<<< ours
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
=======

$homeController = new HomeController($view);
$authController = new AuthController($view);
$dashboardController = new DashboardController($view);
$buildingController = new BuildingController($view);
$blockController = new BlockController($view);
$unitController = new UnitController($view);
$errorController = new ErrorController($view);
>>>>>>> theirs

// Public routes
$router->get('/', [$homeController, 'index']);
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
<<<<<<< ours
$router->post('/logout', [$authController, 'logout']);
=======
$router->get('/logout', [$authController, 'logout']);

// Protected routes (auth middleware would be here in real app)
>>>>>>> theirs
$router->get('/dashboard', [$dashboardController, 'index']);
$router->get('/buildings', [$buildingController, 'index']);
$router->get('/buildings/create', [$buildingController, 'create']);
$router->post('/buildings/store', [$buildingController, 'store']);
$router->get('/buildings/edit', [$buildingController, 'edit']);
$router->post('/buildings/update', [$buildingController, 'update']);
$router->post('/buildings/delete', [$buildingController, 'delete']);

<<<<<<< ours
return $router;
=======
// Buildings
$router->get('/buildings', [$buildingController, 'index']);
$router->get('/buildings/create', [$buildingController, 'create']);
$router->post('/buildings', [$buildingController, 'store']);
$router->get('/buildings/{id:\d+}', [$buildingController, 'show']);
$router->get('/buildings/{id:\d+}/edit', [$buildingController, 'edit']);
$router->put('/buildings/{id:\d+}', [$buildingController, 'update']);
$router->delete('/buildings/{id:\d+}', [$buildingController, 'destroy']);

// Blocks
$router->get('/blocks', [$blockController, 'index']);
$router->get('/blocks/create', [$blockController, 'create']);
$router->post('/blocks', [$blockController, 'store']);
$router->get('/blocks/{id:\d+}', [$blockController, 'show']);
$router->get('/blocks/{id:\d+}/edit', [$blockController, 'edit']);
$router->put('/blocks/{id:\d+}', [$blockController, 'update']);
$router->delete('/blocks/{id:\d+}', [$blockController, 'destroy']);

// Units
$router->get('/units', [$unitController, 'index']);
$router->get('/units/create', [$unitController, 'create']);
$router->post('/units', [$unitController, 'store']);
$router->get('/units/{id:\d+}', [$unitController, 'show']);
$router->get('/units/{id:\d+}/edit', [$unitController, 'edit']);
$router->put('/units/{id:\d+}', [$unitController, 'update']);
$router->delete('/units/{id:\d+}', [$unitController, 'destroy']);

// Error pages
$router->get('/403', [$errorController, 'forbidden']);
$router->get('/404', [$errorController, 'notFound']);
$router->get('/500', [$errorController, 'serverError']);

return $router;
>>>>>>> theirs
