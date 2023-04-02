<?php

namespace App\controllers;

use App\models\UserModel;
use App\models\PuzzleModel;
use App\controllers\UserController;

class AjaxController
{
    private string $request;
    private string $response;
    private UserModel $userModel;
    private PuzzleModel $puzzleModel;

    public function __construct()
    {
        $this->request = $_GET['request'] ?? $_POST['request'];
        $this->userModel = new UserModel();
        $this->puzzleModel = new PuzzleModel();
    }

    /**
     * This function will handle the ajax request sent to perform various actions
     * Every time an ajax request is sent, it comes with a string request to
     * distinguish the action.
     * The request is then handled with a switch and for every possible request
     * a function will take care of the request and provide the data
     *
     * @return void $response
     */
    public function handleRequest(): void
    {
        /**
         * This code uses the ternary operator to take the values from the superGlobal variables
         * $_GET and $_POST. It is used to not make redundancy code because not always there are
         * username, email and password.
         */
        $username = filter_input(INPUT_POST,'username', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        $password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

        $this->userModel->setUsername($username);
        $this->userModel->setEmail($email);
        $this->userModel->setPassword($password);

        $user = new UserController($this->userModel);

        $keyword = filter_input(INPUT_GET,'keyword', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;

        $this->puzzleModel->setKeywords($keyword);





        /**
         * This portion of the code is for the player vs PC. It gets the values
         * sent which are a file name which will be the session_id, 
         * the current position on the board and the skill level
         */

        $fileName = filter_input(INPUT_GET, 'fileName', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        $fen = filter_input(INPUT_GET, 'fen', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;
        $skill = filter_input(INPUT_GET, 'skill', FILTER_SANITIZE_SPECIAL_CHARS) ?? null;


        $fileName = "$fileName.txt";
        $fen = '"' . $fen . '"';


        /**
         * This is the switch that handle the request.
         */
        switch ($this->request) {
            case 'get_move_pc':
                $this->response = $this->getMove($fileName, $fen, $skill);
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

            case 'get_id_puzzle':
                $this->puzzleModel->getPuzzleId();
                $this->response = $this->puzzleModel->getId();
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
     * @param string filename
     * @param string the fen string
     * @param string skill level
     * @return string the move generated
     */
    private function getMove(string $fileName, string $fen, string $skill): string
    {
        $fileName = escapeshellcmd($fileName);
        $fen = escapeshellcmd($fen);
        $skill = escapeshellcmd($skill);


        exec("py ../app/python/main.py $fileName $fen $skill"); //execute the python script

        $file = fopen("../app/generated_files/$fileName", "r"); //open the file created by the script

        $move = fread($file, filesize("../app/generated_files/$fileName")); //assign to a variable the content

        unlink("../app/generated_files/$fileName"); //delete the file created by the python script

        return $move; //return the new position

    }
}
