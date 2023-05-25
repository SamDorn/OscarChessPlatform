<?php

namespace App\controllers;

use App\core\Request;
use App\utilities\Jwt;
use App\core\Controller;
use App\utilities\Email;
use App\models\UserModel;


class UserController extends Controller
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
        if ($this->userModel->validate()) {
            $response = $this->userModel->addUser("normal");
            if ($response === "User added correctly in the database") {
                $response = Email::sendEmail($this->userModel->getEmail(), "normal", $this->userModel->getVerificationCode());
                if($response === 'email sent'){
                    header("Location: login?es");
                } else{
                    header("Location: login?ese");
                }

            } else {
                if($response === "username already taken")
                    header("Location: register?uau");
                elseif ($response === "email already taken") {
                    header("Location: register?eau");
                }
            }
        }
        else{
            header("Location: register?we");
        }
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
                header("Location: login?wc");
        } catch (\Exception $e) {

            echo '<pre>';
            var_dump($e->getMessage());
            echo '</pre>';
            //header("Location: login?ee");
        }
    }
    public function logout(): void
    {
        Jwt::deleteToken();
    }

    /**
     * This function calls the function checkUsername of the UserModel class
     * and checks if the username that the user is typing is available and it is
     * not used by another user.
     *
     * @return void
     */
    public function checkUsername(Request $request): mixed
    {
        $this->userModel->loadData($request->getBody());
        if ($this->userModel->checkUsername())
            return json_encode("Username already taken");
        else
            return json_encode("Username available");
    }

    public function getPlayers(): mixed
    {
        return json_encode($this->userModel->getAll(), JSON_PRETTY_PRINT);
    }

    /**
     * Verify the user's email. If the verification was successfull
     * the user is redirected to the home page, if it isn0t it is redirected 
     * to the home page with an error. 
     * 
     *
     * @param Request http request containing the body and other informations
     * @return void
     */
    public function verifyEmail(Request $request): void
    {
        $data = $request->getBody();
        $this->userModel->setVerificationCode($data['code']);
        if ($this->userModel->verifyEmail()) {
            header("Location: home?ev");
        } else {
            header("Location: home?nv");
        }
    }
    public function getPlayerById(Request $request, array $params): mixed
    {
        $this->userModel->setId($params[1]);
        return json_encode($this->userModel->getUserById());
    }
    public function updateUser(Request $request): void
    {
        $this->userModel->loadData($request->getBody());
        $this->userModel->loadData($this->userModel->getAllInfo());
        $this->userModel->loadData($request->getBody());
        $this->userModel->updateUser();
        header("Location: home?am");
    }
    public function sendEmail(Request $request) : mixed
    {
        $this->userModel->loadData($request->getBody());
        $this->userModel->loadData($this->userModel->getAllInfo());
        $email = $this->userModel->getEmail();
        return json_encode(Email::sendEmail($email, "normal", $this->userModel->getVerificationCode()));
    }
}
