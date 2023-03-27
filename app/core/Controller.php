<?php

namespace App\core;

class Controller
{
    public static function render(string $view, array $params = [])
    {
        extract($params);
        ob_start();
        require_once "../app/views/layout/header.php";
        require_once "../app/views/$view.php";
        ob_end_flush();

    }
}
?>