<?php

namespace App;

class Dispatcher
{
    private $action;
    private $args; // to define. Example /player/matthew

    public function __construct()
    {
        $this->action = "home";
    }

    /**
     * This function of the dispatcher handles the action of the user.
     * It display a certain view based on the action that the user require.
     *
     * @return void
     */
    public function handleAction()
    {
        if (isset($_GET['action'])) {
            $this->action = $_GET['action'];
        }
        switch ($this->action) {
            case 'home':
                if (!isset($_GET['request']) && !isset($_POST['request']))
                    require_once("../app/views/home_page.php");
                break;

            case 'vsComputer':
                require_once("../app/views/versus_computer_page.php");
                break;

            case 'vsPlayer':
                require_once("../app/views/versus_player_page.php");
                break;

            case 'login':
                require_once("../app/views/login_page.php");
                break;
            case 'puzzle':
                require_once("../app/views/puzzles_page.php");
        }
    }
}
