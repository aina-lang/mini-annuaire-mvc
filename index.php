<?php

require_once "app/App.php";
require_once 'controllers/CategoryController.php';
require_once 'controllers/EntryController.php';

use app\App;
use controllers\EntryController;
use controllers\CategoryController;

$app = new App(__DIR__);

$app->router->get('/entries', [EntryController::class, 'index']);
$app->router->get('/entries/add', [EntryController::class, 'add']);
$app->router->post('/entries/store', [EntryController::class, 'store']);
$app->router->get('/entries/{id}/show', [EntryController::class, 'show']);
$app->router->get('/entries/{id}/delete', [EntryController::class, 'delete']);
$app->router->get('/entries/', [EntryController::class, 'index']);
$app->router->get('/entries/add/', [EntryController::class, 'add']);
$app->router->get('/entries/store/', [EntryController::class, 'store']);
$app->router->get('/entries/{id}/show/', [EntryController::class, 'show']);
$app->router->post('/entries/{id}/delete/', [EntryController::class, 'delete']);
$app->router->get('/entries/{id}/edit', [EntryController::class, 'edit']);
$app->router->get('/entries/{id}/edit/', [EntryController::class, 'edit']);

$app->router->get('/categories/{id}/edit', [CategoryController::class, 'edit']);
$app->router->get('/categories/{id}/edit/', [CategoryController::class, 'edit']);
$app->router->get('/', [CategoryController::class, 'index']);
$app->router->get('', [CategoryController::class, 'index']);
$app->router->get('/categories', [CategoryController::class, 'index']);
$app->router->get('/categories/add', [CategoryController::class, 'add']);
$app->router->post('/categories/store', [CategoryController::class, 'store']);
$app->router->get('/categories/{id}/show', [CategoryController::class, 'show']);
$app->router->get('/categories/{id}/delete', [CategoryController::class, 'delete']);
$app->router->get('/categories/', [CategoryController::class, 'index']);
$app->router->get('/categories/add/', [CategoryController::class, 'add']);
$app->router->post('/categories/store/', [CategoryController::class, 'store']);
$app->router->get('/categories/{id}/show/', [CategoryController::class, 'show']);
$app->router->get('/categories/{id}/delete/', [CategoryController::class, 'delete']);

$app->run();
