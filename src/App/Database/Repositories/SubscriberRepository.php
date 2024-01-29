<?php

namespace App\Database\Repositories;

use App\Database\Repositories\BaseRepository;

class SubscriberRepository extends BaseRepository
{
    /**
     * Get the subscriber count
     *
     * @return int
     * @throws \PDOException
     */
    public function getSubscriberCount(): int
    {
        try {
            // Prepare the query
            $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM subscribers');
        
            // Execute the query
            $stmt->execute();
        
            // Return the count
            return (int) $stmt->fetchColumn();
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    /**
     * Get subscribers with pagination
     *
     * @param int $page  The page number
     * @param int $limit The number of items per page
     * @return array<array<string, mixed>> An array of subscribers, each represented as an associative array
     * @throws \PDOException
     */
    public function getSubscribers($page = 1, $limit = 10): array
    {
        try {
            // Calculate the offset
            $offset = ($page - 1) * $limit;

            $stmt = $this->pdo->prepare('SELECT * FROM subscribers ORDER BY created DESC LIMIT :limit OFFSET :offset');

            // Bind the limit and offset parameters
            $stmt->bindValue(':limit', (int) $limit, \PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int) $offset, \PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw $e;
        }
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
     * @throws \PDOException
     */
    public function insertSubscriber($email, $name, $lastName, $status): bool
    {
        try {
            // Begin the transaction
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare('INSERT INTO subscribers (email, name, last_name, status) VALUES (?, ?, ?, ?)');
            $stmt->execute([$email, $name, $lastName, $status]);

            return $this->pdo->commit();
        } catch (\PDOException $e) {
            // Rollback the transaction
            $this->pdo->rollBack();

            error_log($e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if a subscriber exists with a given email
     *
     * @param string $email The subscriber's email
     *
     * @return bool         True if the subscriber exists, false otherwise
     * @throws \PDOException
     */
    public function subscriberExists($email): bool
    {
        try {
            $stmt = $this->pdo->prepare('SELECT * FROM subscribers WHERE email = ?');
            $stmt->execute([$email]);
            return $stmt->fetch() !== false;
        } catch (\PDOException $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }
}
