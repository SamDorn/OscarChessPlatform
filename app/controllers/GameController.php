<?php

namespace App\controllers;

require_once "../app/views/pages.php";


class GameController
{
    public function vsComputer()
    {
        htmlHead();
        scriptsChessboard();
        require_once "../app/views/versus_computer_page.php";
    }

    public function vsPlayer()
    {
        if (!isset($_SESSION["username"])) {
            header('Location: index.php');
        }
        htmlHead();
        scriptsChessboard();
        require_once "../app/views/versus_player.php";
    }
    public function puzzle()
    {
        htmlHead();
        scriptsChessboard();
        require_once "../app/views/puzzles_page.php";
    }
}
