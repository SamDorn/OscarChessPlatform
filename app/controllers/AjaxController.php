<?php

namespace App\controllers;

use \App\models\UserModel;
use Exception;

class AjaxController
{
    private $userModel;
    private $response = "";

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * This function will handle the ajax request sent to perform various actions
     * Every time an ajax request is sent, it comes with a string request to
     * distinguish to action.
     * The request is then handled with a switch and for every possible request
     * a function will take care of the request and provide the data
     *
     * @return string $response
     */
    public function handleRequest()
    {
        if (isset($_GET['request']))
            $request = $_GET['request'];

        if (isset($_POST['request']))
            $request = $_POST['request'];


        if (isset($_POST['username']))
            $username = $_POST['username'];

        if (isset($_POST['email']))
            $email = $_POST['email'];
        /**
         * This portion of the code is for the player vs PC. It gets the values
         * sent which are a file name which will be the session_id, 
         * the current position on the board and the skill level
         */
        if (isset($_GET['fileName']) && isset($_GET['fen'])) {

            $fileName = $_GET["fileName"] . ".txt";

            $fen = '"' . $_GET["fen"] . '"';

            $skill = $_GET["skill"];
        }

        if (isset($_POST['username'])  && isset($_POST['password']) && !isset($_POST['email'])) {

            $username = $_POST["username"];

            $password = hash("sha512", $_POST["password"]);
        }


        /**
         * This portion of the code is for the signUp.
         * It gets the username, email and password
         * Things might change in the future, maybe more field for
         * the registration.
         */
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {

            $username = $_POST["username"];

            $email = $_POST["email"];

            $password = hash("sha512", $_POST["password"]);
        }



        /**
         * This is the switch that handle the request.
         * Currently the are 5 possible request but it is expected that
         * there will be more like for chess PVP and more.
         */
        switch ($request) {
            case 'get_move_pc':
                $this->response = $this->get_move_pc($fileName, $fen, $skill);
                break;

            case 'login':
                $this->response = $this->check($username, $password);
                break;

            case 'signUp':
                $this->response = $this->add($username, $email, $password);
                break;

            case 'checkUsername':
                $this->response = $this->checkUsername($username);
                break;

            case 'checkEmail':
                $this->response = $this->checkEmail($email);
                break;
            default:
                $this->response = 'Invalid request';
                break;
        }

        echo json_encode($this->response);
    }

    /**
     * This function runs the python script found in app/python/main.py.
     * After the python script will create a file where it stores the new position
     * with the move made by the chess engine, this function will read the file
     * content inside that file and will store the new position in $new_fen which
     * will be returned. Need to implement if the user is logged in. Infact if the user
     * is logged in it needs to connect to the database to update the current game
     *
     * @param string $fileName
     * @param string $fen
     * @param string $skill
     * @param boolean $login
     * @return string $new_fen
     */
    private function get_move_pc($fileName, $fen, $skill)
    {
        escapeshellcmd($fileName);
        escapeshellcmd($fen);
        escapeshellcmd($skill);


        exec("py ../app/python/main.py $fileName $fen $skill"); //execute the python script

        $file = fopen("../app/generated_files/$fileName", "r"); //open the file created by the script

        $new_fen = fread($file, filesize("../app/generated_files/$fileName")); //assign to a variable the content

        unlink("../app/generated_files/$fileName"); //delete the file created by the python script

        return $new_fen; //return the new position

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
    private function check($username, $password)
    {
        try {
            if ($this->userModel->checkUser($username, $password)) {
                $_SESSION["username"] = $username;
                return "OK";
            } else
                return "Wrong credentials";
        } catch (Exception) {

            return "Something went wrong";
        }
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
    private function add($username, $email, $password)
    {
        try {
            $this->userModel->addUser($username, $email, $password);
            return "User Signed-Up";
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
    private function checkUsername($username)
    {
        try {
            if ($this->userModel->checkUsername($username))
                return "Username already taken";
            else
                return "Username available";
        } catch (Exception) {
            return "Something went wrong";
        }
    }

    private function checkEmail($email)
    {
        try {
            if ($this->userModel->checkEmail($email))
                return "Email already used";
            else
                return;
        } catch (Exception) {
            return "Something went wrong";
        }
    }
}
