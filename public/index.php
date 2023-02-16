<?php

session_start();
require_once '../vendor/autoload.php';
use App\controllers\AjaxController;
use App\controllers\IndexController;

$action = 'index';
$indexController = new IndexController();

if (isset($_GET['request']) || isset($_POST['request'])) 
{
    $ajaxController = new AjaxController();
    $ajaxController->handleRequest();
    $action = 'nulla';
}

if (isset($_GET['action'])) 
{
    $action = $_GET['action'];
}




switch ($action) {
    case 'index':
        $indexController->index();
        break;
    case 'vsComputer':
        $indexController->vsComputer();
        break;

    case 'login':
        $indexController->login();
        break;
    
    default:
        die();
}
