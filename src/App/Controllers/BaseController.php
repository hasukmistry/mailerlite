<?php
namespace App\Controllers;

use App\Database\Connection as Database;

class BaseController {
	protected $db;

	public function __construct() {
		$this->db = new Database(getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'));
	}
}
