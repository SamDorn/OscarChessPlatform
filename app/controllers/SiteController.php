<?php

namespace App\controllers;

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
    /**
     * Returns a rendered login page with a GoogleController object passed as a
     * parameter needed to create the URL.
     * 
     * @return string A string that represents the rendered login page
     */
    public function login(): string
    {
        $googleController = new GoogleController();
        $params = [
            'googleController' => $googleController
        ];
        return $this->render('login_page', $params);
    }
    /**
     * Returns a rendered 'register_page' with a GoogleController object passed as a
     * parameter needed to create the URL.
     * 
     * @return string A string that represents the rendered 'register_page' view
     */
    public function register(): string
    {
        $googleController = new GoogleController();
        $params = [
            'googleController' => $googleController
        ];
        return $this->render('register_page', $params);
    }
}
