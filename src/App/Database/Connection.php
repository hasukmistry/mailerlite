<?php

namespace App\Database;

use PDO;

class Connection
{
    /**
     * PDO instance
     *
     */
    protected $pdo;

    /**
     * Create a new Connection instance
     *
     * @param string $host     Database host
     * @param string $dbname   Database name
     * @param string $user     Database user
     * @param string $password Database password
     */
    public function __construct($host, $dbname, $user, $password)
    {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $this->pdo = new PDO($dsn, $user, $password);
    }

    /**
     * Get the PDO instance
     *
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Close the PDO connection
     *
     * @return void
     */
    public function __destruct()
    {
        $this->pdo = null;
    }
}
