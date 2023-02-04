<?php

  $fileName =$_GET["fileName"] . ".txt";

  $fen = '"' . $_GET["fen"] . '"';

  $skill = $_GET["skill"];

  exec("py python/main.py $fileName $fen $skill");

  $file = fopen("games/$fileName", "r");
  
  echo json_encode(fread($file,filesize("games/$fileName")));
