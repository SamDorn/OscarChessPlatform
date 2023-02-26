<?php

namespace App\controllers;
use App\models\PvpModel;

class PvpController
{
    private $pvpModel;
    public $username_1;
    public $username_2;
    private $white;
    private $status;

    public function __construct()
    {
        $this->pvpModel = new PvpModel();
        $this->username_1 = null;
        $this->username_2 = null;
        $this->white = null;
        $this->status = null;
    }

    public function setUsername($username)
    {
        $this->username_1 = is_null($this->username_1) ? $username : $this->username_1;
        $this->username_2 = $this->username_1 != $username ? $username : null;
    }
    public function createGame()
    {
        $this->pvpModel->createGame($this->username_1);
    }
    public function addSecondUsername()
    {
        $this->pvpModel->addUsername($this->username_1, $this->username_2);
    }
}