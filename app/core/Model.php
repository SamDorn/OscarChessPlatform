<?php

namespace App\core;

use App\core\Database;



class Model
{

    protected \PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }
    public function loadData($data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function validate(array $rules)
    {
        /**
         * NEED TO BE IMPLEMENTED
         */
    }
}
