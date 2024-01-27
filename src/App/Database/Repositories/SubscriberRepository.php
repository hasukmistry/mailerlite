<?php

namespace App\Database\Repositories;

use App\Database\Repositories\BaseRepository;

class SubscriberRepository extends BaseRepository {
    /**
     * Get the subscriber count
     *
     * @return int
     */
    public function getSubscriberCount(): int
    {
        // Prepare the query
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM subscribers');
    
        // Execute the query
        $stmt->execute();
    
        // Return the count
        return (int) $stmt->fetchColumn();
    }

    /**
     * Get subscribers with pagination
     *
     * @param int $page  The page number
     * @param int $limit The number of items per page
     * @return array<array<string, mixed>> An array of subscribers, each represented as an associative array
     */
    public function getSubscribers( $page = 1, $limit = 10 ): array
    {
        // Calculate the offset
        $offset = ($page - 1) * $limit;

        $stmt = $this->pdo->prepare('SELECT * FROM subscribers ORDER BY created DESC LIMIT :limit OFFSET :offset');

        // Bind the limit and offset parameters
        $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Insert a new subscriber
     *
     * @param string $email    The subscriber's email
     * @param string $name     The subscriber's first name
     * @param string $lastName The subscriber's last name
     * @param string $status   The subscriber's status
     *
     * @return bool            True on success, false on failure
     */
    public function insertSubscriber($email, $name, $lastName, $status): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO subscribers (email, name, last_name, status) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$email, $name, $lastName, $status]);
    }

    /**
     * Check if a subscriber exists with a given email
     *
     * @param string $email The subscriber's email
     *
     * @return bool         True if the subscriber exists, false otherwise
     */
    public function subscriberExists($email) {
        $stmt = $this->pdo->prepare('SELECT * FROM subscribers WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }
}
