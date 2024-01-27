<?php

namespace App\Database\Repositories;

use App\Database\Connection;

class BaseRepository {
	protected $pdo;

	public function __construct(Connection $connection) {
		$this->pdo = $connection->getPdo();
	}
}
