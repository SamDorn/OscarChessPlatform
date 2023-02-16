<?php
    class Database{
        private $host = "127.0.0.1";
        private $user = "root";
        private $password = "";
        private $db_name= "OscarChessPlatform";
        public $con;

        public function __construct(){
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
              ];
            $this->con = new PDO("mysql:host=$this->host;dbname=$this->db_name;", $this->user, $this->password, $options);
        }

        public function getConnection(){
            return $this->con;
        }
    }
?>