<?php

namespace App\controllers;

class AjaxController
{

    public function handleRequest()
    {
        $fileName = '';
        $fen = '';

        if (isset($_GET['fileName']) && isset($_GET['fen'])) {

            $fileName = $_GET["fileName"] . ".txt";

            $fen = '"' . $_GET["fen"] . '"';

            $skill = $_GET["skill"];
        }

        $skill = $_GET["skill"];

        $request = $_REQUEST['request'];

        $response = '';


        switch ($request) {
            case 'get_move_no_login':
                $response = $this->get_move_pc($fileName, $fen, $skill);


                break;


            default:
                $response = 'Invalid request';
                break;
        }

        echo json_encode($response);
    }


    public function get_move_pc($fileName, $fen, $skill, $login = false)
    {
        exec("py ../app/python/main.py $fileName $fen $skill");
        try {
            $file = fopen("../app/generated_files/$fileName", "r");

            $new_fen = fread($file, filesize("../app/generated_files/$fileName"));
            unlink("../app/generated_files/$fileName");

            return $new_fen;
        } catch (\ErrorException) {
            return 'gameOver';
        }
    }
}
