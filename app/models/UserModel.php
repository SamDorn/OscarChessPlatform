<?php

namespace App\models;

class UserModel
{
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }

    public function addUser($username, $email, $password)
    {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }
}