<?php

namespace App\models;
use App\models\Model;

 

class UserModel extends Model
{
    private $username;
    private $email;
    private $password;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Undocumented function
     *
     * @param [type] $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * Undocumented function
     *
     * @param [type] $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    /**
     * Undocumented function
     *
     * @param [type] $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPassword()
    {
        return $this->password;
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
    public function addUser()
    {
        $query = "INSERT INTO users (id, username, email, password) VALUES (null, :username, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':username', $this->username);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':password', $this->password);
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
    public function checkUser()
    {
        $query = "SELECT * FROM users WHERE username = :username AND password = :password";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
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
    public function checkUsername()
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
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
    public function checkEmail()
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $result = $stmt->fetch();

        if($result > 0)
            return true;
        else
            return false;
    }
}
?>