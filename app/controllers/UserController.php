<?php

namespace App\controllers;

class UserController
{
    private $userModel;

    public function __construct($userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * This function add the user in the database
     * It calls the addUser method from the UserModel class
     * It takes $username, $email and $password which are taken from
     * the post request.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return string success or fail
     */
    public function add()
    {
        try {
            $this->userModel->addUser();
            return "User Signed-Up";
        } catch (\Exception) {
            return "Something went wrong";
        }
    }
    /** 
     * This function calls the checkUser function of the UserModel
     * class with checks if the username and password match with a 
     * record in the database
     *
     * @param string $username
     * @param string $password
     * @return string success or fail
     */
    public function check()
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
     * @param string $username
     * @return string is available or not
     */
    public function checkUsername()
    {
        try {
            if ($this->userModel->checkUsername())
                return "Username already taken";
            else
                return "Username available";
        } catch (\Exception) {
            return "Something went wrong";
        }
    }
    /**
     * This function check if the email that the user is trying to sign up with is
     * already used.
     *
     * @param string $email
     * @return void
     */
    public function checkEmail()
    {
        try {
            if ($this->userModel->checkEmail())
                return "Email already used";
            else
                return;
        } catch (\Exception) {
            return "Something went wrong";
        }
    }
}
?>
