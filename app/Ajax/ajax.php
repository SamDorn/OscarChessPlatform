<?php
  $fileName = $_GET["fileName"] . ".txt";

  $fen = '"' . $_GET["fen"] . '"';

  $skill = $_GET["skill"];

  exec("python ../Python/main.py $fileName $fen $skill");

  $file = fopen("../GeneratedFiles/$fileName", "r");
  
  echo json_encode(fread($file,filesize("../GeneratedFiles/$fileName")));