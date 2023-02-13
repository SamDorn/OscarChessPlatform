<?php

session_start();
require_once '../vendor/autoload.php';
use App\controllers\AjaxController;
use App\controllers\IndexController;

$action = 'index';

if (isset($_GET['request'])) 
{
    $ajaxController = new AjaxController();
    $ajaxController->handleRequest();
    $action = 'nulla';
}

if (isset($_GET['action'])) 
{
    $action = $_GET['action'];
}


$indexController = new IndexController();

switch ($action) {
    case 'index':
        $indexController->index();
        break;
    case 'vsComputer':
        $indexController->vsComputer();
        break;
    
    default:
        die();
}
?>