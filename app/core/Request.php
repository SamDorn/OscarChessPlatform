<?php

namespace App\core;

class Request
{

    public function getPath()
    {
        $path = $_SERVER['REQEST_URI'] ?? '/';
        $position = strpos($path, '?');
        var_dump($position);
    }
    public function getMethod()
    {

    }
}