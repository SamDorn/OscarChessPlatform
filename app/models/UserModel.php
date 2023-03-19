<?php

namespace App\models;

use App\models\Model;

class UserModel extends Model
{
    private $id;
    private $username;
    private $email;
    private $password;
    private $avatar;
    private $type;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Undocumented function
     *
     * @param string $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * Undocumented function
     *
     * @param string $username
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }
    /**
     * Undocumented function
     *
     * @param string $email
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }
    /**
     * Undocumented function
     *
     * @param string $password
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * This function add the user to the database 
     * It uses a prepared statement and bindParam
     * to avoid SQL Injection
     * 
     * @return string 
     */
    public function addUser(): string
    {
        $query = "INSERT INTO users (id, username, email, password) VALUES (null, :username, :email, :password)";
        try {
            $this->conn->beginTransaction();
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':username', $this->username);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':password', $this->password);
            $stmt->execute();
            $this->conn->commit();

            return "User added correctly in the database";
        } catch (\PDOException) {
            
            return "There was a problem adding the user in the database";
        }
    }



    /**
     * This function check if the user is in the database
     * It uses a prepared statement and bindParam
     * to avoid SQL Injection
     * 
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

        if ($result > 0)
            return true;
        else
            return false;
    }
    /**
     * This function is used to check if a the user who is creating a new account is 
     * using an available username, if there is already a user with that username
     * is returned false otherwise is returned true.
     * 
     * @return bool true if available
     */
    public function checkUsername()
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result > 0)
            return true;
        else
            return false;
    }

    /**
     * This function is used to check if the user who is creating a new account is 
     * using an email never used before.
     *
     * @return bool true if available
     */
    public function checkEmail()
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result > 0)
            return true;
        else
            return false;
    }
}
