<?php

namespace App\controllers;

require_once "../app/views/pages.php";

class HomeController
{
    public function home()
    {
        htmlHead();
        require_once "../app/views/home_page.php";
    }
    public function login()
    {
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        $googleController = new GoogleController(null);
        htmlHead();
        require_once "../app/views/login_page.php";
    }
}
?>