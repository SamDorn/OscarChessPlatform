<?php

namespace App\controllers;

class IndexController
{
    public function index()
    {
        require_once '../app/views/index_page.php';
    }

    public function vsComputer()
    {
        require_once '../app/views/versus_computer.php';
    }
}