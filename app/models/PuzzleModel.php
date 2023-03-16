<?php

namespace App\models;

class Puzzle extends Model
{
    private $id;
    private $elo;
    private $keywords;

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

}
?>