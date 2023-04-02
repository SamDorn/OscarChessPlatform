<?php

namespace App\core;

use App\database\Database;



abstract class Model
{
    public const RULE_REQUIRED = 'required';
    public const RULE_EMAIL = 'required';
    public const RULE_MIN = 'required';
    public const RULE_MAX = 'required';
    public const RULE_UNIQUE = 'required';

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
    abstract public function rules(): array;
    public function validate()
    {

    }
}
