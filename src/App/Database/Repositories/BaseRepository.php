<?php

namespace App\Database\Repositories;

use App\Database\Connection;

class BaseRepository
{
    /**
     * PDO instance
     *
     */
    protected $pdo;

    /**
     * Create a new BaseRepository instance
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->pdo = $connection->getPdo();
    }
}
