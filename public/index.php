<?php
session_start();

require_once '../vendor/autoload.php';
use App\controllers\AjaxController;
use App\controllers\IndexController;
use App\controllers\PVPController;

$action = 'index';
$indexController = new IndexController();

if (isset($_GET['request']) || isset($_POST['request'])) 
{
    $ajaxController = new AjaxController();
    $ajaxController->handleRequest();
    $action = '';
    
}

if (isset($_GET['action'])) 
{
    $action = $_GET['action'];
}
if (isset($_POST['pvp'])) 
{
    $PVPController = new PVPController();
    $PVPController->handleRequest();
    $action = '';
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

    case 'vsPlayer':
        $indexController->vsPlayer();
        break;
    
    default:
        die();
}
