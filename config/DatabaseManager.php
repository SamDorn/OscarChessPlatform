<?php
    class DatabaseManager{
        private $host = "127.0.0.1";
        private $user = "root";
        private $passwod = "";
        private $db_name= "OscarChessPlatform";

        public function __construct(){
            $con = new PDO("mysql:host=$this->host;dbname=$this->db_name;$this->user, $this->password");
        }
    }