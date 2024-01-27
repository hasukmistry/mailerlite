<?php

namespace App\Utils;

use ReflectionMethod;

class Request
{
    /**
     * The defined routes for the rest api
     *
     * @var array
     */
    protected array $restRoutes;

    /**
     * Request path
     *
     * @var string
     */
    protected string $requestPath;

    /**
     * Request method
     *
     * @var string
     */
    protected string $requestMethod;

    /**
     * Parsed request path
     *
     * @var string
     */
    protected string $parsedRequestPath = '';

    /**
     * Create a new Request instance
     *
     */
    public function __construct()
    {
        $this->restRoutes    = Routes::getRoutes();
        $this->requestPath   = trim($_SERVER['PATH_INFO'], '/');
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Execute the request from defined routes for the rest api
     *
     * @return void
     */
    public function run(): void
    {
        // Current routes configuration do not have query string based matching
        // So we should remove the query string from the requested path
        $this->mayBeRemoveQueryString()
            ->setResponseHeaders()
            ->handleRoute();
    }

    /**
     * Check if current request is an OPTIONS request
     *
     * @return bool
     */
    public function isOptionsRequest(): bool
    {
        return $this->requestMethod === 'OPTIONS';
    }

    /**
     * Handle request route and call the defined callback
     *
     * @return void
     */
    protected function handleRoute(): void
    {
        if (!$this->isRouteDefined()) {
            $this->sendJsonResponse(['error' => 'Not found'], 404);
            die();
        }

        if (!$this->isMethodDefined()) {
            $this->sendJsonResponse(['error' => 'Method not allowed'], 405);
            die();
        }

        // If the request method is OPTIONS, just exit - This will allow CORS to work on the preflight request
        if ($this->isOptionsRequest()) {
            $this->setPreflightResponse();
            die();
        }

        // This should be handle after the preflight request
        if (!$this->isRequestMethodAllowed()) {
            $this->sendJsonResponse(['error' => 'Method not allowed'], 405);
            die();
        }

        // lets create an instance of given controller class in the callback
        $controllerClass = $this->restRoutes[$this->parsedRequestPath]['callback'][0];
        $controllerMethod = $this->restRoutes[$this->parsedRequestPath]['callback'][1];

        $controller = $this->isMethodStatic(
            $controllerClass,
            $controllerMethod
        ) ? $controllerClass : new $controllerClass();

        // call the callback
        call_user_func([$controller, $controllerMethod]);
    }

    /**
     * Check if requested route is defined
     *
     * @return bool
     */
    protected function isRouteDefined(): bool
    {
        return array_key_exists($this->parsedRequestPath, $this->restRoutes);
    }

    /**
     * Check if requested method is defined
     *
     * @return bool
     */
    protected function isMethodDefined(): bool
    {
        return array_key_exists('method', $this->restRoutes[$this->parsedRequestPath]);
    }

    /**
     * Check if requested method matches with defined method in routes
     *
     * @return bool
     */
    protected function isRequestMethodAllowed(): bool
    {
        return $this->restRoutes[$this->parsedRequestPath]['method'] === $this->requestMethod;
    }

    /**
     * Check if a given class method is static.
     *
     * @param string $className  The name of the class
     * @param string $methodName The name of the method
     * @return bool              True if the method is static, false otherwise
     */
    protected function isMethodStatic(string $className, string $methodName): bool
    {
        $method = new ReflectionMethod($className, $methodName);
        return $method->isStatic();
    }

    /**
     * Remove the query string from the requested path
     * See: https://www.php.net/manual/en/function.parse-url.php#106731
     *
     * @return self
     */
    protected function mayBeRemoveQueryString(): self
    {
        // Parse the URL into its components
        $parsedUrl = parse_url($this->requestPath);
    
        // Check if the 'path' key exists in the parsed URL
        if (isset($parsedUrl['path'])) {
            $this->parsedRequestPath = $parsedUrl['path'];
        } else {
            // If it doesn't, return the original URL
            $this->parsedRequestPath = $this->requestPath;
        }

        return $this;
    }

    /**
     * Set the response headers for the API request
     *
     * @return self
     */
    protected function setResponseHeaders(): self
    {
        header('Content-Type: application/json');
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        return $this;
    }

    /**
     * Set the headers for the preflight request
     *
     * @return self
     */
    protected function setPreflightResponse(): self
    {
        header('HTTP/1.1 200 OK');

        return $this;
    }

    /**
     * Send a json response
     *
     * @param array $data         The data to send
     * @param integer $statusCode The status code to send
     *
     * @return void
     */
    protected function sendJsonResponse(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        echo json_encode($data);
    }
}
