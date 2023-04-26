<?php

namespace App\models;

use App\core\Model;
use Exception;

class UserModel extends Model
{
    protected int $id;
    protected ?string $username;
    protected ?string $email;
    protected ?string $password;
    protected ?string $passwordConfirm;
    protected ?string $avatar = "https://i.pinimg.com/originals/f1/0f/f7/f10ff70a7155e5ab666bcdd1b45b726d.jpg";
    protected ?int $last_connectionId;
    protected ?string $status;
    protected ?string $type;
    protected ?bool $verified;
    protected ?string $verificationCode;
    protected ?string $date;

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
    public function setVerificationCode(?string $verificationCode){
        $this->verificationCode = $verificationCode;
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

    public function getVerificationCode() :?string
    {
        $query = "SELECT verification_code FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':email', $this->email);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['verification_code'];
    }
    public function rules(): array
    {
        return [
            'username' => [self::RULE_REQUIRED, self::RULE_UNIQUE],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, self::RULE_UNIQUE],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 24]],
            'passwordConfirm' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match' => 'password']]
        ];
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
        $code = bin2hex(random_bytes(16));
        $this->verificationCode = $type != "normal" ? null : $code;
        $this->verified = $type != "normal" ? true : false;
        $query = "INSERT INTO users (id, username, email, password, avatar, last_connectionId,
        status, type, verified, verification_code, date) VALUES (null, :username, :email, :password, :avatar, null, null, :type, :verified, :verification_code, NOW())";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':username', $this->username);
            $stmt->bindValue(':email', $this->email);
            $stmt->bindValue(':password', $this->password != null ? password_hash($this->password, PASSWORD_BCRYPT) : null);
            $stmt->bindValue(':avatar', $this->avatar);
            $stmt->bindValue(':type', $type);
            $stmt->bindValue(':verified', $this->verified);
            $stmt->bindValue(':verification_code', $code);
            $stmt->execute();

            return "User added correctly in the database";
        } catch (\PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Checks if the user is in the database
     * 
     * @return bool
     */
    public function checkUser(string $type): bool
    {
        $query = "SELECT id, password FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result) {
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
     * @return bool true if username available
     */
    public function checkUsername(): bool
    {
        $query = "SELECT id FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result)
            return true;
        else
            return false;
    }

    /**
     * Verify the user email 
     *
     * @return boolean true if the verification was successfull and false
     * if it wasn't
     */
    public function verifyEmail(): bool
    {
        $this->conn->beginTransaction();
        try{
            $query = "SELECT email FROM users WHERE verification_code = :verification_code";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':verification_code', $this->verificationCode);
            $stmt->execute();
            $result = $stmt->fetch();
            if($result)
                $this->email = $result['email'];
            else
                return false;

            $query = "UPDATE users SET verified = 1 WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            $query = "UPDATE users SET verification_code = null WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $this->email);
            $stmt->execute();
            $this->conn->commit();
            return true;
        }
        catch(Exception){
            $this->conn->rollBack();
            return false;
        }
        
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
        $result = $stmt->fetch() ? $stmt->fetch() : [];
        return $result;
    }
    /**
     * Retrieves a user's data from the database by their ID.
     * 
     * @param int id  Is an integer representing the unique identifier of a user in the
     * database.
     * 
     * @return ?array An array containing the user information for the user with the specified ID.
     */
    public function getUserById(int $id): ?array
    {
        $query = "SELECT * FROM users where id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ?? null;
    }

}
