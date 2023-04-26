<?php

namespace App\models;

use App\core\Model;


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
    /**
     * Pick a random ID from the puzzles table and assign it
     * to the id property
     *
     * @return void
     */
    public function getPuzzleId() : void
    {
        $query = "SELECT id FROM puzzles WHERE keywords LIKE :keywords ORDER BY RAND() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $var = "%" . $this->keywords . "%";
        $stmt->bindParam(':keywords', $var, \PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->id = $result['id'];
    }
    public function rules(): array
    {
        return [];
    }
}
