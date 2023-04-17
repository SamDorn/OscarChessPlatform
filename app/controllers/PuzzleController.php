<?php

namespace App\controllers;

use App\models\PuzzleModel;

class PuzzleController
{
    private PuzzleModel $puzzleModel;

    public function __construct()
    {
        $this->puzzleModel = new PuzzleModel();
    }
    public function puzzleId(): mixed
    {
        $this->puzzleModel->getPuzzleId();
        return json_encode($this->puzzleModel->getId());
    }
}