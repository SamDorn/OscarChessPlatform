<?php

namespace App\controllers;

use App\core\Controller;
use App\core\Request;

class GameController extends Controller
{
    public function getGame(Request $request, array $params)
    {
        echo '<pre>';
        var_dump($params);
        echo '</pre>';
    }
}