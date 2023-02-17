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

    /**
     * This function add the user to the database 
     * It uses a prepared statement and bindParam
     * to avoid SQL Injection
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return void
     */
    public function addUser($username, $email, $password)
    {
        $query = "INSERT INTO user (id, username, email, password) VALUES (null, :username, :email, :password)";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }


    /**
     * This function check if the user is in the database
     * It uses a prepared statement and bindParam
     * to avoid SQL Injection
     *
     * @param string $username
     * @param string $password
     * @return bool
     */
    public function checkUser($username, $password)
    {
        $query = "SELECT * FROM user WHERE username = :username AND password = :password";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result > 0)
            return true;
        else
            return false;
    }

    public function checkUsername($username)
    {
        $query = "SELECT * FROM user WHERE username = :username";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result > 0)
            return true;
        else
            return false;
    }

    public function checkEmail($email)
    {
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result > 0)
            return true;
        else
            return false;
    }
}