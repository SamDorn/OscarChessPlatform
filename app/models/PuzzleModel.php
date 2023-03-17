<?php

namespace App\models;

class PuzzleModel extends Model
{
    private $id;
    private $elo;
    public $keywords;

    public function __construct()
    {
        parent::__construct();
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setElo($elo)
    {
        $this->elo = $elo;
    }
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }
    public function getId(){
        return $this->id;
    }
    public function getPuzzleId() : void
    {
        $total = $this->getPuzzleCount();
        $rand = rand(0, (int)($total));
        $query = "SELECT id FROM puzzles WHERE keywords LIKE :keywords LIMIT 1 OFFSET $rand";
        $stmt = $this->conn->prepare($query);
        $var = "%" . $this->keywords . "%";
        $stmt->bindParam(':keywords', $var, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->id = $result['id'];
    }
    private function getPuzzleCount() : string
    {

        $query = "SELECT COUNT(id) as total FROM puzzles WHERE keywords LIKE :keywords";
        $stmt = $this->conn->prepare($query);
        $var = "%" . $this->keywords . "%";
        $stmt->bindParam(':keywords', $var, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

}
?>