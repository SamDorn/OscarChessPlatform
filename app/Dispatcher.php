<?php

namespace App;

use App\controllers\AjaxController;
use App\controllers\GameController;
use App\controllers\GoogleController;
use App\controllers\HomeController;

class Dispatcher
{
    private string $args; // to define. Example /player/matthew

    

    /**
     * Every request that isn't an ajax request will have an action, if it doesn't
     * it will be an ajax request.
     *
     * @return void
     */
    public function handleAction() : void
    {
        $action = isset($_GET['action']) ? ($_GET['action']) : "request";

        switch ($action) {
            case 'home':
                $homeController = new HomeController();
                $homeController->home();
                break;

            case 'vsComputer':
                $gameController = new GameController();
                $gameController->vsComputer();
                break;

            case 'vsPlayer':
                $gameController = new GameController();
                $gameController->vsPlayer();
                break;

            case 'login':
                $homeController = new HomeController();
                $homeController->login();
                break;
            case 'puzzle':
                $gameController = new GameController();
                $gameController->puzzle();
                break;

            case 'google':
                $code = $_GET["code"];
                $googleController = new GoogleController($code);
                $googleController->handleLogin();
                break;
            case 'request':
                $request = $_POST['request'] ?? $_GET['request'];
                $ajaxController = new AjaxController($request);
                $ajaxController->handleRequest();
                break;
            default:
                http_response_code(404);
                break;

        }
    }
}
