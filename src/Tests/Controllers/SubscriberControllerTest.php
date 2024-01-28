<?php

namespace Tests\Controllers;

use App\Controllers\SubscriberController;
use App\Database\Repositories\SubscriberRepository;
use App\Utils\JsonResponse;
use App\Utils\Validator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class SubscriberControllerTest extends TestCase
{
    private $subscriberController;
    private $repositoryMock;
    private $validatorMock;
    private $jsonResponseMock;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(SubscriberRepository::class);
        $this->validatorMock = $this->createMock(Validator::class);
        $this->jsonResponseMock = $this->createMock(JsonResponse::class);

        $this->subscriberController = new SubscriberController();

        $reflection = new ReflectionClass($this->subscriberController);

        $repositoryProperty = $reflection->getProperty('repository');
        $repositoryProperty->setAccessible(true);
        $repositoryProperty->setValue($this->subscriberController, $this->repositoryMock);

        $validatorProperty = $reflection->getProperty('validator');
        $validatorProperty->setAccessible(true);
        $validatorProperty->setValue($this->subscriberController, $this->validatorMock);

        $responseProperty = $reflection->getProperty('response');
        $responseProperty->setAccessible(true);
        $responseProperty->setValue($this->subscriberController, $this->jsonResponseMock);
    }

    public function testCreateSubscriber(): void
    {
        // Define the input data
        $inputData = ['email' => 'test@example.com', 'name' => 'Test', 'status' => 'active'];

        // Define the expected output
        $expectedOutput = ['status' => 'success'];

        // Set up the validator mock
        $this->validatorMock->method('setInputData')->willReturn($this->validatorMock);
        $this->validatorMock->method('setEmailRules')->willReturn($this->validatorMock);
        $this->validatorMock->method('setNameRules')->willReturn($this->validatorMock);
        $this->validatorMock->method('setStatusRules')->willReturn($this->validatorMock);
        $this->validatorMock->method('setOptionalLastNameRules')->willReturn($this->validatorMock);
        $this->validatorMock->method('validate')->willReturn($this->validatorMock);
        $this->validatorMock->method('getValidatedData')->willReturn($inputData);
        $this->validatorMock->method('hasValidationPassed')->willReturn(true);

        // Set up the repository mock
        $this->repositoryMock->method('subscriberExists')->willReturn(false);
        $this->repositoryMock->method('insertSubscriber')->willReturn(true);

        // Set up the json response mock
        $this->jsonResponseMock->expects($this->once())
            ->method('sendJsonResponse')
            ->with($this->equalTo($expectedOutput), $this->equalTo(201));

        // Call the createSubscriber met
        $this->jsonResponseMock->expects($this->once())
            ->method('terminateResponseStream');

        $this->subscriberController->createSubscriber();
    }

    public function testGetSubscribers(): void
    {
        // Define the expected output
        $expectedOutput = [
            'data' => [
                ['email' => 'test1@example.com', 'name' => 'Test1', 'status' => 'active'],
                ['email' => 'test2@example.com', 'name' => 'Test2', 'status' => 'inactive']
            ],
            'paginate' => [
                'current' => 1,
                'total' => 1,
                'records' => 2
            ]
        ];

        // Set up the repository mock
        $this->repositoryMock->method('getSubscribers')->willReturn($expectedOutput['data']);
        $this->repositoryMock->method('getSubscriberCount')->willReturn($expectedOutput['paginate']['records']);

        // Set up the json response mock
        $this->jsonResponseMock->expects($this->once())
            ->method('sendJsonResponse')
            ->with($this->equalTo($expectedOutput), $this->equalTo(200));

        // Call the getSubscribers method
        $this->subscriberController->getSubscribers();
    }
}
