<?php

namespace App\controllers;
use App\models\UserModel;
use Exception;

class UserController
{
    private $userModel;
    private $username;
    private $email;
    private $password;

    public function __construct($username, $email, $password)
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->userModel = new UserModel();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPassword()
    {
        return $this->password;
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
            $this->userModel->addUser($this);
            return "User Signed-Up";
        } catch (Exception) {
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
            if ($this->userModel->checkUser($this->username, $this->password)) {
                $_SESSION["username"] = $this->username;
                return "OK";
            } else
                return "Wrong credentials";
        } catch (Exception) {

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
            if ($this->userModel->checkUsername($this->username))
                return "Username already taken";
            else
                return "Username available";
        } catch (Exception) {
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
            if ($this->userModel->checkEmail($this->email))
                return "Email already used";
            else
                return;
        } catch (Exception) {
            return "Something went wrong";
        }
    }




}