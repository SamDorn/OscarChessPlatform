<?php

namespace App\core;

class Request
{

    /**
     * It returns the path of the current request.
     * 
     * @return string path of the request.
     */
    public function parseUrl($routes): array
    {
        $callback = null;
        $params = array();
        $path = trim($_SERVER['REQUEST_URI'], '/');

        // $this->routes[
        //     'get' => [
        //         '/home', [SiteController::class, home],
        //         '/login', [SiteController::class, login],
        //         'player/{id}', [SiteController::class, player]
        //     ]
        // ]
        /**
         * If the preg_match was successfull $matches will contain an array of
         * 2 element $matches = ['/player', [42]]. Since the
         * parameter is needed is unset and left only with the parameter
         */
        
        foreach ($routes[$this->getMethod()] as $route => $handler) {
            if (preg_match("%^$route%", $path, $matches) === 1) {
                $callback = $handler;
                unset($matches[0]);
                $params = $matches;
                break;
            }
        }
        return array($callback, $params);
    }


    /**
     * It returns the request method in lowercase
     * 
     * @return string The request method.
     */
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
    /**
     * This function returns true if the request method is GET
     * 
     * @return bool The method of the request.
     */
    public function isGet(): bool
    {
        return $this->getMethod() === 'get';
    }
    /**
     * This function returns true if the request method is POST, otherwise it returns false.
     * 
     * @return bool The method of the request.
     */
    public function isPost(): bool
    {
        return $this->getMethod() === 'post';
    }

    /**
     * It takes the request method and returns the body of the request.
     * 
     * @return array body of the request.
     */
    public function getBody(): array
    {
        $body = [];
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                $body[$key] = htmlspecialchars($body[$key]);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                $body[$key] = htmlspecialchars($body[$key]);
            }
        }
        return $body;
    }
}
