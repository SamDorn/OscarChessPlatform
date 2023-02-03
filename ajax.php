<?php
  exec('py python/main.py Oscar.txt "' . $_GET["fen"] . '"');
  $file = fopen("Oscar.txt", "r");
  echo json_encode(fread($file,filesize("Oscar.txt")));