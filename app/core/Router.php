<?php

namespace App\core;

class Router
{
    private Controller $controller;
    private Request $request;
    public Response $response;
    public array $routes = [];
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
     * Assign to the array routes the key
     * get and the path and the value to the mixed 
     * callable which can be a function, a string or an array.
     *
     * @param string $path
     * @param mixed $callback
     * @return void
     */
    public function get(string $path, mixed $callback) : void
    {
        $this->routes['get'][substr($path,1)] = $callback;
    }
    /**
     * Assign to the array routes the key
     * post and the path and the value to the mixed 
     * callable which can be a function, a string or an array.
     *
     * @param string $path
     * @param mixed $callback
     * @return void
     */
    public function post(string $path, mixed $callback) : void
    {
        $this->routes['post'][substr($path,1)] = $callback;
    }
    /**
     * Based on the request made(URL) check if there
     * is a valid method that corrisponds to the 
     * path. If none is found a 404 code is thrown 
     * If it's just a string it renders the view.
     * If it's an array or callable invoke the call_user_func
     * which will execute the function.
     *
     * @return mixed html page, json
     */
    public function resolve() : mixed
    {
        // Get the path from the URL localhost/something. Gets the /something
        $path = $this->request->parseUrl()[0];
        
        // Get the method from the HTTP request.
        $method = $this->request->getMethod();
        //Get the param from the URL
        $param = $this->request->parseUrl()[1] ?? false;
        // Assign the callable from the routes array and false if it doesn't exist
        $callback = $this->routes[$method][$path] ?? false;

        // Throws a 404 error if it doesn't exist and render the view
        if (!$callback) {
            Application::$app->response->setStatusCode(404);
            /**
             * Which approach is better static or non static??
             */
            //return Controller::render("_404");
            $this->controller = new Controller();
            return $this->controller->render("_404");
        }
        // If the callback provided is a string it just renders the view
        if (is_string($callback)) {
            $this->controller = new Controller();
            return $this->controller->render($callback);
        }

        // If it's an array it need to 
        if(is_array($callback)){
            $callback[0] = new $callback[0]();
            //Application::$app->controller = new $callback[0]();
            //$callback[0] = Application::$app->controller;
        }

        /**
         * Execute the method inside the object with will be $callback[0]
         * and the method which will be in $callback[1].
         * call_user_func allow an array as first argument only if it 
         * has two items with 0->class/object 1->method
         */
        return call_user_func($callback, $this->request, $param,);
    }/*
    public function renderView($view, $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view , $params);
        return str_replace("{{ content }}", $viewContent, $layoutContent);
    }
    public function renderContent($viewContent)
    {
        $layoutContent = $this->layoutContent();
        return str_replace("{{ content }}", $viewContent, $layoutContent);
    }
    protected function layoutContent()
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/main.php";
        return ob_get_clean();
    }
    protected function renderOnlyView($view, $params)
    {
        extract($params);
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }*/


}
