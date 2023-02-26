<?php

namespace App;

class Dispatcher
{
    private $action;
    private $args; // to define. Example /player/matthew

    public function __construct()
    {
        $this->action = "index";
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
            case 'index':
                if (!isset($_GET['request']) && !isset($_POST['request']))
                    require_once("../app/views/index_page.php");
                break;

            case 'vsComputer':
                require_once("../app/views/versus_computer_page.php");
                break;

            case 'vsPlayer':
                require_once("../app/views/versus_player.php");
                break;

            case 'login':
                require_once("../app/views/login_page.php");
                break;
        }
    }
}
