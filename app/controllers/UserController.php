<?php

namespace App\controllers;

use App\core\Application;
use App\core\Request;
use App\models\UserModel;

class UserController
{
    private  $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * This function add the user in the database
     * It calls the addUser method from the UserModel class
     * @return string
     */


    public function addUser(Request $request): void
    {
        $this->userModel->loadData($request->getBody());
        echo '<pre>';
        var_dump($this->userModel);
        echo '</pre>';
    }
    public function add(): string
    {

        return $this->userModel->addUser();
    }
    /** 
     * This function calls the checkUser function of the UserModel
     * class with checks if the username and password match with a 
     * record in the database
     *
     * @return string
     */
    public function check(): string
    {
        try {
            if ($this->userModel->checkUser()) {
                $_SESSION["username"] = $this->userModel->getusername();
                return "OK";
            } else
                return "Wrong credentials";
        } catch (\Exception) {

            return "Something went wrong";
        }
    }

    /**
     * This function calls the function checkUsername of the UserModel class
     * and checks if the username that the user is typing is available and it is
     * not used by another user.
     *
     * @return string 
     */
    public function checkUsername(): string
    {
        if ($this->userModel->checkUsername())
            return "Username already taken";
        else
            return "Username available";
    }
    /**
     * This function check if the email that the user is trying to sign up with is
     * already used.
     *
     * @return string
     */
    public function checkEmail(): string
    {
        try {
            if ($this->userModel->checkEmail())
                return "Email already used";
            else
                return "";
        } catch (\Exception) {
            return "Something went wrong";
        }
    }
}
