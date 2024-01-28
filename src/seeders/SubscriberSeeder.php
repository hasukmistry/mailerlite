<?php

require_once './vendor/autoload.php';

use Faker\Factory;

class SubscriberSeeder
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function seed()
    {
        $faker = Factory::create();

        $stmt = $this->pdo->prepare("
            INSERT INTO subscribers (email, name, last_name, status)
            VALUES (:email, :name, :last_name, :status)
        ");

        for ($i = 0; $i < 50; $i++) {
            $stmt->execute([
                'email' => $faker->unique()->safeEmail,
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'status' => $faker->randomElement(['active', 'inactive']),
            ]);
        }
    }
}

// Usage:
$pdo = new PDO(
    sprintf('mysql:host=%s;dbname=%s', getenv('MYSQL_HOST'), getenv('MYSQL_DATABASE')),
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD')
);

$seeder = new SubscriberSeeder($pdo);

$seeder->seed();

echo "Data seeding completed.\n";
