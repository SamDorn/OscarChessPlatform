<?php
class Database
{
    private $host = "127.0.0.1";
    private $user = "root";
    private $password = "";
    private $db_name = "OscarChessPlatform";
    public $con;

    public function __construct()
    {
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->con = new PDO("mysql:host=$this->host;dbname=$this->db_name;", $this->user, $this->password, $options);
        $this->createTables();
    }

    public function getConnection()
    {
        return $this->con;
    }

    private function createTables()
    {
        $cmd = "CREATE TABLE IF NOT EXISTS users(
            id INT NOT NULL AUTO_INCREMENT,
            username VARCHAR(30) NOT NULL, 
            email VARCHAR(255) NOT NULL, 
            password VARCHAR(128) NOT NULL,
            PRIMARY KEY ( id ))";
        $cmd2 = "CREATE TABLE IF NOT EXISTS games_pvp_in_progress(
            id INT NOT NULL AUTO_INCREMENT,
            username_1 VARCHAR(30) NOT NULL,
            username_2 VARCHAR(30),
            connection_1 int NOT NULL,
            connection_2 int,
            status VARCHAR(30) NOT NULL,
            pgn VARCHAR(50),
            white VARCHAR(30),
            PRIMARY KEY ( id ))";

        $this->con->exec($cmd);
        $this->con->exec($cmd2);
    }
}
