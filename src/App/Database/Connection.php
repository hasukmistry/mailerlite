<?php

namespace App\Database;

use PDO;

class Connection
{
    private $pdo;

    public function __construct($host, $dbname, $user, $password)
    {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $this->pdo = new PDO($dsn, $user, $password);
    }

    public function getPdo()
    {
        return $this->pdo;
    }

    public function __destruct()
    {
        $this->pdo = null;
    }
}
