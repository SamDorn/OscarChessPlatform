<?php
  $fileName = $_GET["fileName"] . ".txt";

  $fen = '"' . $_GET["fen"] . '"';

  $skill = $_GET["skill"];

  exec("py ../Python/main.py $fileName $fen $skill");
  try{

    $file = fopen("../GeneratedFiles/$fileName", "r");
  
    echo json_encode(fread($file,filesize("../GeneratedFiles/$fileName")));
  }catch(Exception $e){

    echo json_encode("gameOver");
    
  }