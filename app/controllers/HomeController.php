<?php

namespace App\controllers;

use App\core\Controller;

class HomeController
{
    public function home() : void
    {
        Controller::render("home_page");
    }
    public function login() : void
    {
        $error = $_GET['error'] ?? null;
        $googleController = new GoogleController(null);
        
        Controller::render("login_page", array(
            "error" => $error,
            "googleController" => $googleController
        ));
    }
}