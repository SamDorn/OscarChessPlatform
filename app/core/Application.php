<?php

namespace App\core;

class Application
{
    public static string $ROOT_DIR;
    public Router $router;
    public Request $request;
    public static Application $app;
    public Controller $controller;

    public function __construct($rootPath)
    {
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->router = new Router($this->request);
    }
    /**
     * Echo out the value of the resolve method of 
     * of the router class
     *
     * @return void
     */
    public function run(): void
    {
        echo $this->router->resolve();
    }
}
