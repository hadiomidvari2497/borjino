<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\BlockController;
use App\Controllers\BuildingController;
use App\Controllers\DashboardController;
use App\Controllers\ErrorController;
use App\Controllers\HomeController;
use App\Controllers\UnitController;
use App\Support\Router;
use App\Support\View;

session_start();

$router = new Router();
$view = new View(dirname(__DIR__) . '/resources/views');

$homeController = new HomeController($view);
$authController = new AuthController($view);
$dashboardController = new DashboardController($view);
$buildingController = new BuildingController($view);
$blockController = new BlockController($view);
$unitController = new UnitController($view);
$errorController = new ErrorController($view);

// Public routes
$router->get('/', [$homeController, 'index']);
$router->get('/login', [$authController, 'showLogin']);
$router->post('/login', [$authController, 'login']);
$router->get('/logout', [$authController, 'logout']);

// Protected routes (auth middleware would be here in real app)
$router->get('/dashboard', [$dashboardController, 'index']);

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