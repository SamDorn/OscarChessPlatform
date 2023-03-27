<?php

session_start();

require_once '../vendor/autoload.php';
//var_dump($_SERVER["REQUEST_URI"]);

use App\Dispatcher;
use App\core\Application;

$dispatcher = new Dispatcher();
$dispatcher->handleAction();
//$app = new Application();
//$app->router->
//$app->run();
?>