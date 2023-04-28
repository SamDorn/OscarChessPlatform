<?php

require_once '../vendor/autoload.php';

ini_set("display_errors", 1);

use Dotenv\Dotenv;
use App\core\Application;
use App\controllers\SiteController;
use App\controllers\UserController;
use App\controllers\GoogleController;
use App\controllers\PuzzleController;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = new Application(dirname(__DIR__));

/* These lines of code are defining the routes for the application using the GET method. Each route is
associated with a specific controller method that will handle the request and return the appropriate
response. For example, when a user navigates to the '/home' route, the 'home' method in the
'SiteController' class will be called to handle the request and return the appropriate response. */
$app->router->get('/home', [SiteController::class, 'home']);
$app->router->get('/vsComputer', [SiteController::class, 'vsComputer']);
$app->router->get('/vsPlayer', [SiteController::class, 'vsPlayer']);
$app->router->get('/puzzles', [SiteController::class, 'puzzles']);
$app->router->get('/login', [SiteController::class, 'login']);
$app->router->get('/register', [SiteController::class, 'register']);
$app->router->get('/verifyEmail', [UserController::class, 'verifyEmail']);
$app->router->get('/google', [GoogleController::class, 'handleLogin']);
$app->router->get('/puzzle', [PuzzleController::class, 'puzzleId']);



/* These lines of code are defining the routes for the application using the POST method. Each route is
associated with a specific controller method that will handle the request and return the appropriate
response. For example, when a user submits a registration form, the 'addUser' method in the
'UserController' class will be called to handle the request and return the appropriate response.*/
$app->router->post('/register', [UserController::class, 'addUser']);
$app->router->post('/login', [UserController::class, 'checkUser']);
$app->router->post('/checkUsername', [UserController::class, 'checkUsername']);


/**
 * API
 */
$app->router->get('/api', [SiteController::class, 'api']);
$app->router->get('/api/players', [UserController::class, 'getPlayers']);


/* `->run();` is a method call that starts the application and handles the incoming HTTP request by
matching the requested URL with the defined routes and calling the appropriate controller method to
handle the request and return the response. */
$app->run();