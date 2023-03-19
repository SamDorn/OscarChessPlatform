<?php

namespace App;

use App\controllers\AjaxController;
use App\controllers\GameController;
use App\controllers\GoogleController;
use App\controllers\HomeController;

class Dispatcher
{
    private $action;
    private $args; // to define. Example /player/matthew

    

    /**
     * Undocumented function
     *
     * @return void
     */
    public function handleAction()
    {
        $this->action = isset($_GET['action']) ? ($_GET['action']) : "request";

        switch ($this->action) {
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
                $request = isset($_POST['request']) ? $_POST['request'] : $_GET['request'];
                $ajaxController = new AjaxController($request);
                $ajaxController->handleRequest();
                break;
            default:
                http_response_code(404);
                break;

        }
    }
}
