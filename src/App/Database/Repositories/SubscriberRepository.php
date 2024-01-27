<?php

namespace App\Database\Repositories;

use App\Database\Repositories\BaseRepository;

class SubscriberRepository extends BaseRepository {
	public function getSubscriberCount() {
		// Prepare the query
		$stmt = $this->pdo->prepare('SELECT COUNT(*) FROM subscribers');
	
		// Execute the query
		$stmt->execute();
	
		// Return the count
		return $stmt->fetchColumn();
	}

	public function getSubscribers( $page = 1, $limit = 10 ) {
		// Calculate the offset
		$offset = ($page - 1) * $limit;

		$stmt = $this->pdo->prepare('SELECT * FROM subscribers ORDER BY created DESC LIMIT :limit OFFSET :offset');

		// Bind the limit and offset parameters
		$stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
		$stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
		
		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function insertSubscriber($email, $name, $lastName, $status) {
		$stmt = $this->pdo->prepare('INSERT INTO subscribers (email, name, last_name, status) VALUES (?, ?, ?, ?)');
		$stmt->execute([$email, $name, $lastName, $status]);
	}

	public function subscriberExists($email) {
		$stmt = $this->pdo->prepare('SELECT * FROM subscribers WHERE email = ?');
		$stmt->execute([$email]);
		return $stmt->fetch() !== false;
	}
}
