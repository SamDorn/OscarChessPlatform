<?php

namespace App\core;

use App\core\Database;



class Model
{

    protected \PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }
    public function loadData($data)
    {
        foreach($data as $key => $value){
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }
}
