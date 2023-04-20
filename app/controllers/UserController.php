<?php

namespace App\controllers;

use App\core\Request;
use App\utilitis\Jwt;
use App\utilitis\Email;
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
        $response = $this->userModel->addUser("normal");
        if ($response === "User added correctly in the database") {

            Email::sendEmail($this->userModel->getEmail());
            
        } else
            header("Location:login?error=01");
    }
    /** 
     * This function calls the checkUser function of the UserModel
     * class with checks if the username and password match with a 
     * record in the database
     *
     * @return string
     */
    public function checkUser(Request $request): void
    {
        try {
            $this->userModel->loadData($request->getBody());
            if ($this->userModel->checkUser("normal")) {
                Jwt::createToken($this->userModel);
                header("Location: home");
            } else
                echo "Wrong credentials";
        } catch (\Exception) {

            echo "Qualcosa è andato storto";
        }
    }

    /**
     * This function calls the function checkUsername of the UserModel class
     * and checks if the username that the user is typing is available and it is
     * not used by another user.
     *
     * @return void
     */
    public function checkUsername(Request $request): void
    {
        $this->userModel->loadData($request->getBody());
        if ($this->userModel->checkUsername())
            echo json_encode("Username already taken");
        else
            echo json_encode("Username available");
    }

    public function getPlayers(): mixed
    {
        return json_encode($this->userModel->getAll());
    }
}
