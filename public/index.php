<?php

session_start();

require_once '../vendor/autoload.php';

use App\core\Application;
use App\controllers\AjaxController;
use App\controllers\SiteController;
use App\controllers\UserController;
use App\controllers\GoogleController;

$app = new Application(dirname(__DIR__));

$app->router->get('/home', [SiteController::class, 'home']);
$app->router->get('/home', [SiteController::class, 'home']);
$app->router->get('/vsComputer', [SiteController::class, 'vsComputer']);
$app->router->get('/vsPlayer', [SiteController::class, 'vsPlayer']);
$app->router->get('/puzzles', [SiteController::class, 'puzzles']);
$app->router->get('/login', [SiteController::class, 'login']);
$app->router->post('/checkUsername', [UserController::class, 'checkUsername']);
$app->router->get('/ajax', [AjaxController::class, 'handleRequest']);
$app->router->post('/ajax', [AjaxController::class, 'handleRequest']);
$app->router->get('/google', [GoogleController::class, 'handleLogin']);
$app->router->post('/register', [UserController::class, 'addUser']);

$app->run();
?>