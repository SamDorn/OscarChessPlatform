<?php
    require "../config/DatabaseManager.php";
    class User{
        private string $username;
        private string $email;
        private string $password;
        private bool $verified;

        public function __construct($username, $email, $password){
            $this->username = $username;
            $this->email = $email;
            $this->password = hash("sha256", $password);
            $this->verified = false;
            
            $con = new DatabaseManager();

            $this->addUser($con);
        }

        private function addUser($con){
            $query = "INSERT INTO user (id, username, email, password, verified) VALUES (?, ?, ?, ?)";
            $result = $con->prepare($query);
            $result->execute([null, $this->username, $this->email, $this->password, $this->verified]);


        }
    }