<?php

namespace App\core;

use App\utilities\Jwt;

class Controller
{

    /**
     * It takes a view name and an array of parameters, extracts the parameters, and returns the
     * rendered view
     * 
     * @param string view Name of the view to render.
     * @param array parameters Array of parameters that will be passed to the view.
     * 
     * @return string The rendered view.
     */
    public function render(string $view, array $params = []): string
    {
        $token = $_COOKIE['jwt'] ?? null;
        if($token){
            if(!Jwt::validate($token)){
            
                //Application::$app->response->setStatusCode(401);
                unset($_COOKIE['jwt']);
                setcookie('jwt', null, -1, '/'); 
                header("Location: login?error=03");
            }
        }
        
        extract($params);
        ob_start();
        require_once Application::$ROOT_DIR . "/app/views/layout/header.php";
        require_once Application::$ROOT_DIR . "/app/views/$view.php";
        return ob_get_clean();
    }
}
