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
    public function addUser($user)
    {
        $query = "INSERT INTO users (id, username, email, password) VALUES (null, :username, :email, :password)";
        $stmt = $this->con->prepare($query);
        $stmt->bindValue(':username', $user->getUsername());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $user->getPassword());
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
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
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
    /**
     * This function is used to check if a the user who is creating a new account is 
     * using an available username, if there is already a user with that username
     * is returned false otherwise is returned true.
     *
     * @param string $username
     * @return bool true if available, false otherwise is there is already a user with that username
     */
    public function checkUsername($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->con->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result > 0)
            return true;
        else
            return false;
    }

    /**
     * This function is used to check if the user who is creating a new account is 
     * using an email never used before.
     *
     * @param string $email
     * @return bool true if available, false is there is already a user with that email
     */
    public function checkEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
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