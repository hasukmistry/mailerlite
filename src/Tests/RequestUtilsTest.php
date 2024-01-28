<?php

namespace Tests;

use App\Utils\Request;
use App\Utils\JsonResponse;
use PHPUnit\Framework\TestCase;

class RequestUtilsTest extends TestCase
{
    protected function setUp(): void
    {
        // Initial values
        $_SERVER['PATH_INFO'] = '';
        $_SERVER['REQUEST_METHOD'] = '';
    }

    public function testIsOptionsRequest()
    {
        $_SERVER['REQUEST_METHOD'] = 'OPTIONS';
        $request = new Request([], new JsonResponse());
        $this->assertTrue($request->isOptionsRequest());
    
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $request = new Request([], new JsonResponse());
        $this->assertFalse($request->isOptionsRequest());
    }

    private function setTestData($parsedRequestPath = 'test/route'): array
    {
        // Create a mock class with a static method and a non-static method
        $mock = new class {
            public static function staticMethod()
            {
                echo json_encode(['success' => 'static method called']);
            }
            public function nonStaticMethod()
            {
                echo json_encode(['success' => 'non static method called']);
            }
        };

        $restRoutes = [
            'test/route' => [
                'method' => 'GET',
                'callback' => [get_class($mock), 'staticMethod'],
            ],
            'test/route2' => [
                'method' => 'GET',
                'callback' => [get_class($mock), 'nonStaticMethod'],
            ],
        ];

        // Create a mock of JsonResponse
        $jsonResponseMock = $this->getMockBuilder(JsonResponse::class)
            ->onlyMethods(['setResponseHeaders', 'setPreflightResponseHeaders', 'sendJsonResponse'])
            ->getMock();

        // Define the behavior for the setResponseHeaders method
        $jsonResponseMock->method('setResponseHeaders')
            ->willReturn($jsonResponseMock);

        // Define the behavior for the setPreflightResponseHeaders method
        $jsonResponseMock->method('setPreflightResponseHeaders')
            ->willReturn($jsonResponseMock);

        $jsonResponseMock->method('sendJsonResponse')
            ->will($this->returnCallback(function ($data, $statusCode = 200) {
                echo json_encode($data);
            }));

        $request = new Request($restRoutes, $jsonResponseMock);

        // Get the reflection class
        $reflection = new \ReflectionClass(Request::class);

        // Get the property
        $property = $reflection->getProperty('parsedRequestPath');

        // Make it accessible
        $property->setAccessible(true);
    
        // Set the value
        $property->setValue($request, $parsedRequestPath);

        return [
            'request'    => $request,
            'mock'       => get_class($mock),
            'reflection' => [
                'class'    => $reflection,
                'property' => $property,
            ],
        ];
    }

    public function testIsRouteDefined()
    {
        $testData = $this->setTestData();

        // Set reflection for protected method
        $method = new \ReflectionMethod(Request::class, 'isRouteDefined');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($testData['request']));
    }

    public function testIsMethodDefined()
    {
        $testData = $this->setTestData();

        // Set reflection for protected method
        $method = new \ReflectionMethod(Request::class, 'isMethodDefined');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($testData['request']));
    }

    public function testIsRequestedMethodAllowed()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $testData = $this->setTestData();

        // Set reflection for protected method
        $method = new \ReflectionMethod(Request::class, 'isRequestMethodAllowed');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($testData['request']));
    }

    public function testIsMethodStatic()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $testData = $this->setTestData();

        // Set reflection for protected method
        $method = new \ReflectionMethod(Request::class, 'isMethodStatic');
        $method->setAccessible(true);

        $this->assertTrue(
            $method->invoke($testData['request'], $testData['mock'], 'staticMethod')
        );

        $this->assertFalse(
            $method->invoke($testData['request'], $testData['mock'], 'nonStaticMethod')
        );
    }

    public function testMayBeRemoveQueryStringFromRequestPath()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'test/route2?q=lorem';

        $testData = $this->setTestData('test/route2?q=lorem');

        // Set reflection for protected method
        $method = new \ReflectionMethod(Request::class, 'mayBeRemoveQueryStringFromRequestPath');
        $method->setAccessible(true);
        $method->invoke($testData['request']);

        // Get the property from the reflection class
        $property = $testData['reflection']['property'];

        $this->assertEquals('test/route2', $property->getValue($testData['request']));
    }

    public function testHandleRoute()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['PATH_INFO'] = 'test/route';

        // Start output buffering
        ob_start();

        $testData = $this->setTestData('test/route');

        // Set reflection for protected method
        $method = new \ReflectionMethod(Request::class, 'handleRoute');
        $method->setAccessible(true);
        $method->invoke($testData['request']);

        // Get the output and end output buffering
        $output = ob_get_contents();

        ob_get_clean();

        $this->assertEquals(json_encode(['success' => 'static method called']), $output);
    }
}
