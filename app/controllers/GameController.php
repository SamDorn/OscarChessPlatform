<?php

namespace App\controllers;

use App\core\Controller;

class GameController
{
    public function vsComputer() : void
    {
        Controller::render("versus_computer_page");
    }

    public function vsPlayer() : void
    {
        if (!isset($_SESSION["username"])) {
            header('Location: index.php');
        }
        Controller::render("versus_player_page");
    }
    public function puzzle() : void
    {
        Controller::render("puzzles_page");
    }
}
