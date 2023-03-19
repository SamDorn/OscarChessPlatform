<?php

session_start();

require_once '../vendor/autoload.php';

use App\Dispatcher;

$dispatcher = new Dispatcher();
$dispatcher->handleAction();

?>