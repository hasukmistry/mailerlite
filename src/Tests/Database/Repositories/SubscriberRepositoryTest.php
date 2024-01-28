<?php

namespace Tests\Database\Repositories;

use App\Database\Connection;
use App\Database\Repositories\SubscriberRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SubscriberRepositoryTest extends TestCase
{
    private $connection;
    private $pdo;
    private $stmt;
    private $subscriberRepository;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->pdo = $this->createMock(PDO::class);

        $this->connection->expects($this->any())
            ->method('getPdo')
            ->willReturn($this->pdo);
            
        $this->subscriberRepository = new SubscriberRepository($this->connection);
    }

    public function testGetSubscriberCount()
    {
        $this->stmt = $this->createMock(PDOStatement::class);

        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchColumn')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->assertEquals(1, $this->subscriberRepository->getSubscriberCount());
    }

    public function testGetSubscribers()
    {
        $testSubscribers = [
            'data' => [
                [
                    'id' => 1,
                    'email' => 'test@dummy.com',
                    'name' => 'Test User',
                    'status' => 'active',
                    'created' => '2020-01-01 00:00:00'
                ],
            ],
            'paginate' => [
                'total'   => 1,
                'current' => 1,
                'records' => 1,
            ]
        ];

        $this->stmt = $this->createMock(PDOStatement::class);
        
        $this->stmt->expects($this->once())
            ->method('execute');

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn($testSubscribers);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->assertEquals($testSubscribers, $this->subscriberRepository->getSubscribers());
    }

    public function testInsertSubscriber()
    {
        $this->stmt = $this->createMock(PDOStatement::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->assertTrue(
            $this->subscriberRepository->insertSubscriber('test@dummy.com', 'Test User', 'User', 'active')
        );
    }

    public function testSubscriberExists()
    {
        $this->stmt = $this->createMock(PDOStatement::class);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmt);

        $this->stmt->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $this->stmt->expects($this->once())
            ->method('fetch')
            ->willReturn(1);

        $this->assertTrue($this->subscriberRepository->subscriberExists('test@dummy.com'));
    }
}
