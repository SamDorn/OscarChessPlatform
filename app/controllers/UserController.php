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
                if($response === 'problem with sending the email'){
                    echo "problem";
                    //header("Location: registartion?error=02");
                }
                //header("Location: login");

            } else {
                echo "problem database";
                //header("Location: registartion");
            }
        }
        else{
            echo "problem validate";
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
            var_dump($this->userModel);
            if ($this->userModel->checkUser("normal")) {
                Jwt::createToken($this->userModel);
                header("Location: home");
            } else
                header("Location: login?wc=1");
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
            header("Location: home?ev=t");
        } else {
            header("Location: home?nv=t");
        }
    }
    public function getPlayerById(Request $request, array $params): mixed
    {
        $this->userModel->setId($params[1]);
        return json_encode($this->userModel->getUserById());
    }
}
