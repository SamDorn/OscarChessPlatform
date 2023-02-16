<?php

namespace App\models;

use Database;

require_once '../config/db_connection.php';

class UserModel
{
    private $con;

    public function __construct()
    {
        $database = new Database();
        $this->con = $database->getConnection();
    }

    public function addUser($username, $email, $password)
    {
        $query = "INSERT INTO user (id, username, email, password) VALUES (null, :username, :email, :password)";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }

    public function checkUser($username, $password){
        $query = "SELECT * FROM user WHERE username = :username AND password = :password";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetchAll();
    }
}