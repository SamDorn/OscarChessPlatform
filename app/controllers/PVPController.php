<?php

namespace App\controllers;

use \App\models\PVPModel;
use Exception;

class PVPController
{
    private $PVPModel;
    private $response = "";

    public function __construct()
    {
        $this->PVPModel = new PVPModel();
    }

    public function handleRequest()
    {
        if (isset($_POST['pvp'])){
            $action= $_POST['pvp'];
            $username = $_POST['username'];
        }

        switch($action){

            case 'new_game':
                $this->newGame($username);
                break;
        }
        echo json_encode($this->response);
    }
    private function newGame($username)
    {
        if(!$this->PVPModel->checkNewGame()){
            $this->PVPModel->createGame($username);
            $this->response = "Created a game";
        }
        else
        {
            $this->PVPModel->enterGame($username);
            $this->response = "Entered a game";
        }
            
    }
}