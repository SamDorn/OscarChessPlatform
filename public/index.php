<?php
session_start();
//var_dump($_GET['action']);

require_once '../vendor/autoload.php';

use App\controllers\AjaxController;
use App\Dispatcher;

$dispatcher = new Dispatcher();
$dispatcher->handleAction();

if (isset($_GET['request']) || isset($_POST['request'])) {
    $ajaxController = new AjaxController();
    $ajaxController->handleRequest();
}
?>