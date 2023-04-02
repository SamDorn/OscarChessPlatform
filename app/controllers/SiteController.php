<?php

namespace App\controllers;

use App\core\Request;
use App\core\Controller;

class SiteController extends Controller
{
    /**
     * It returns the rendered view of the home_page.php file.
     * 
     * @return string 
     */
    public function home(): string
    {
        return $this->render('home_page');
    }
    /**
     * It returns the rendered view of the contact page.
     * 
     * @return string contact page is being returned.
     */
    public function vsComputer(): string
    {
        return $this->render('versus_computer_page');
    }
    public function vsPlayer(): string
    {
        return $this->render('versus_player_page');
    }
    public function puzzles(): string
    {
        return $this->render('puzzles_page');
    }
    public function login(): string
    {
        $googleController = new GoogleController();
        $params = [
            'googleController' => $googleController
        ];
        return $this->render('login_page', $params);
    }
    /**
     * It takes the request, gets the body of the request, and then dumps the body to the screen.
     * 
     * @param Request request The request object.
     */
    public function handleContact(Request $request)
    {
        $body = $request->getBody();
        echo '<pre>';
        var_dump($body);
        echo '</pre>';
        return 'Handling submitted data';
    }
}
