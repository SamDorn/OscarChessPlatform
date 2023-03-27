<?php

namespace App\core;

class Router
{
    private Request $request;
    protected array $routes = [];
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    public function resolve()
    {
        $this->request->getPath();
    }
}