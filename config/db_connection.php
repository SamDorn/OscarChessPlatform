<?php
    class Database{
        private $host = "127.0.0.1";
        private $user = "root";
        private $password = "";
        private $db_name= "OscarChessPlatform";
        public $con;

        public function __construct(){
            $con = new PDO("mysql:host=$this->host;dbname=$this->db_name;", $this->user, $this->password);
        }

        public function getConnection(){
            return $this->con;
        }
    }
?>