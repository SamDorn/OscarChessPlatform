<?php

namespace App\models;

use App\models\Model;

class UserModel extends Model
{
    private int $id;
    private ?string $username;
    private ?string $email;
    private ?string $password;
    private ?string $avatar;
    private ?string $type;

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Undocumented function
     *
     * @param int $id
     * @return void
     */
    public function setId(int $id) : void
    {
        $this->id = $id;
    }
    /**
     * Undocumented function
     *
     * @param ?string $username
     * @return void
     */
    public function setUsername(?string $username) : void
    {
        $this->username = $username;
    }
    /**
     * Undocumented function
     *
     * @param ?string $email
     * @return void
     */
    public function setEmail(?string $email) : void
    {
        $this->email = $email;
    }
    /**
     * Undocumented function
     *
     * @param ?string $password
     * @return void
     */
    public function setPassword(?string $password) : void
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
            $stmt->bindValue(':password', password_hash($this->password, PASSWORD_BCRYPT));
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
    public function checkUser() : bool
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result > 0){
            if(password_verify($this->password, $result["password"]))
                return true;
            else
                return false;
        }
        else
            return false;
    }
    /**
     * This function is used to check if the user who is creating a new account is
     * using an available username, if there is already a user with that username
     * is returned false otherwise is returned true.
     * 
     * @return bool true if available
     */
    public function checkUsername() : bool
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
    public function checkEmail() : bool
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
