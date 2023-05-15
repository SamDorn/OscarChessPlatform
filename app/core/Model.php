<?php

namespace App\core;


class Model
{

    protected \PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }
    /**
     * Loads data into object properties if they exist.
     * 
     * @param data  is an array containing key-value pairs where the keys represent the property
     * names of an object and the values represent the values to be assigned to those properties. The
     * function iterates through the array and assigns the values to the corresponding properties of
     * the object if they exist.
     */
    public function loadData(array $data): void
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
