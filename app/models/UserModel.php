<?php

namespace App\models;

use App\core\Model;

class UserModel extends Model
{
    protected int $id;
    protected ?string $username;
    protected ?string $email;
    protected ?string $password;
    protected ?string $avatar = "https://i.pinimg.com/originals/f1/0f/f7/f10ff70a7155e5ab666bcdd1b45b726d.jpg";
    protected ?int $last_connectionId;
    protected ?string $status;
    protected ?string $type;
    protected ?bool $verified;

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
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    /**
     * Undocumented function
     *
     * @param ?string $username
     * @return void
     */
    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }
    /**
     * Undocumented function
     *
     * @param ?string $email
     * @return void
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }
    /**
     * Undocumented function
     *
     * @param ?string $password
     * @return void
     */
    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }
    /**
     * Undocumented function
     *
     * @param [type] $avatar
     * @return void
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }
    public function setLast_ConnectionId(?int $last_connectionId): void
    {
        $this->last_connectionId = $last_connectionId;
    }
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
    public function setType(?string $type): void
    {
        $this->type = $type;
    }
    public function setVerified(?bool $verified): void
    {
        $this->verified = $verified;
    }
    public function getLast_ConnectionId(): int
    {
        return $this->last_connectionId;
    }
    public function getStatus(): string
    {
        return $this->status;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getVerified(): bool
    {
        return $this->verified;
    }
    /**
     * Undocumented function
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * This function add the user to the database 
     * It uses a prepared statement and bindParam
     * to avoid SQL Injection
     * 
     * @return string 
     */
    public function addUser(string $type): string
    {
        $this->verified = $type != "normal" ? true : false;
        $query = "INSERT INTO users (id, username, email, password, avatar, last_connectionId,
        status, type, verified) VALUES (null, :username, :email, :password, :avatar, null, null, :type, :verified)";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':username', $this->username);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':password', $this->password != null ? password_hash($this->password, PASSWORD_BCRYPT) : null);
            $stmt->bindValue(':avatar', $this->avatar);
            $stmt->bindValue(':type', $type);
            $stmt->bindValue(':verified', $this->verified);
            $stmt->execute();

            return "User added correctly in the database";
        } catch (\PDOException) {
            return "There was a problem adding the user in the database";
        }
    }

    /**
     * Checks if the user is in the database
     * 
     * @return bool
     */
    public function checkUser(string $type): bool
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result > 0) {
            if ($type === "normal") {
                if (password_verify($this->password, $result["password"])) {
                    $this->setId($result['id']);
                    return true;
                } else
                    return false;
            } else {
                $this->setId($result['id']);
                return true;
            }
        } else
            return false;
    }
    /**
     * Checks if the user who is creating a new account is
     * using an available username, if there is already a user with that username
     * is returned false otherwise is returned true.
     * 
     * @return bool true if available
     */
    public function checkUsername(): bool
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
     * Get all the users from the database
     *
     * @return array
     */
    public function getAll(): array
    {
        $query = "SELECT * FROM users";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
    /**
     * Retrieves a user's data from the database by their ID.
     * 
     * @param int id  Is an integer representing the unique identifier of a user in the
     * database.
     * 
     * @return array An array containing the user information for the user with the specified ID.
     */
    public function getUserById(int $id): array
    {
        $query = "SELECT * FROM users where id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result;
    }
}
