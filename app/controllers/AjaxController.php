<?php

namespace App\controllers;

use App\models\UserModel;
use App\controllers\UserController;

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
     * distinguish the action.
     * The request is then handled with a switch and for every possible request
     * a function will take care of the request and provide the data
     *
     * @return string $response
     */
    public function handleRequest()
    {
        /**
         * This code uses the ternary operator to take the values from the superGlobal variables
         * $_GET and $_POST. It is use to not make redundancy code because not always there are
         * username, email and password.
         */
        $request = $_POST['request'];
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $password = isset($_POST['password']) ? hash("sha512", $_POST["password"]) : null;

        $user = new UserController($username, $email, $password);



        /**
         * This portion of the code is for the player vs PC. It gets the values
         * sent which are a file name which will be the session_id, 
         * the current position on the board and the skill level
         */

        $fileName = isset($_POST['fileName']) ? $_POST['fileName'] : null;
        $fen = isset($_POST['fen']) ? $_POST['fen'] : null;
        $skill = isset($_POST['skill']) ? $_POST['skill'] : null;

        $fileName = "$fileName.txt";
        $fen = '"' . $fen . '"';


        /**
         * This is the switch that handle the request.
         */
        switch ($request) {
            case 'get_move_pc':
                $this->response = $this->get_move_pc($fileName, $fen, $skill);
                break;

            case 'login':
                $this->response = $user->check();
                break;

            case 'signUp':
                $this->response = $user->add();
                break;

            case 'checkUsername':
                $this->response = $user->checkUsername();
                break;

            case 'checkEmail':
                $this->response = $user->checkEmail();
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
}
