<?php

  set_error_handler(function ($errno, $errstr, $errfile, $errline){
    
  
  throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  });
  $fileName = $_GET["fileName"] . ".txt";

  $fen = '"' . $_GET["fen"] . '"';

  $skill = $_GET["skill"];

  exec("py ../Python/main.py $fileName $fen $skill");
  try{

    $file = fopen("../GeneratedFiles/$fileName", "r");
  
    echo json_encode(fread($file,filesize("../GeneratedFiles/$fileName")));
  }catch(ErrorException $e){

    echo json_encode("gameOver");
    
  }
  restore_error_handler();