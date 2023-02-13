<?php

namespace App\controllers;

class AjaxController
{

    public function handleRequest()
    {
        $fileName = $_GET["fileName"] . ".txt";
        
        $fen = '"' . $_GET["fen"] . '"';
        
        $skill = $_GET["skill"];
        
        $request = $_REQUEST['request'];
        $response = '';


        switch ($request) {
            case 'get_move_no_login':
                $response = $this->get_move_no_login($fileName, $fen, $skill);
                break;
            
            
            default:
                $response = 'Invalid request';
                break;
        }

        echo json_encode($response);
    }

    public function get_move_no_login($fileName, $fen, $skill)
    {/*
        set_error_handler(function ($errno, $errstr, $errfile, $errline)
        {
            throw new \Exception($errstr, 0, $errno, $errfile, $errline);
        });
        */
        exec("py ../app/python/main.py $fileName $fen $skill");
        try
        {
            $file = fopen("../app/generated_files/$fileName", "r");
        
            return fread($file,filesize("../app/generated_files/$fileName"));

        }catch(\ErrorException)
        {
           return 'gameOver';
        }
        //restore_error_handler();
    }
}

