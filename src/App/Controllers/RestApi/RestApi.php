<?php

namespace App\Controllers\RestApi;

use ReflectionMethod;

class RestApi {
	const API_NAMESPACE = 'mailerlite/v1';

	public function getRoutes() {
		return [
			self::API_NAMESPACE . '/subscriber' => [
				'method'   => 'POST',
				'callback' => [
					\App\Controllers\SubscriberController::class,
					'createSubscriber'
				]
			],
			self::API_NAMESPACE . '/subscribers' => [
				'method'   => 'GET',
				'callback' => [
					\App\Controllers\SubscriberController::class,
					'getSubscribers'
				]
			],
		];
	}

	public function isMethodStatic($className, $methodName) {
		$method = new ReflectionMethod($className, $methodName);
		return $method->isStatic();
	}

	public function handleRequest() {
		// Get the request method and path
		$requestMethod = $_SERVER['REQUEST_METHOD'];
		$requestPath = trim($_SERVER['PATH_INFO'], '/');

		$parsedRequestPath = $this->removeQueryString( $requestPath );
		
		$this->setResponseHeaders();

		// check if route exists
		if (array_key_exists($parsedRequestPath, $this->getRoutes())) {
			// check if method exists
			if (array_key_exists('method', $this->getRoutes()[$parsedRequestPath])) {
				// check if method is allowed
				if ($this->getRoutes()[$parsedRequestPath]['method'] === $requestMethod) {
					// lets create an instance of given controller class in the callback
					$controllerClass = $this->getRoutes()[$parsedRequestPath]['callback'][0];
					$controllerMethod = $this->getRoutes()[$parsedRequestPath]['callback'][1];

					if ($this->isMethodStatic($controllerClass, $controllerMethod)) {
						// call the callback
						call_user_func([$controllerClass, $controllerMethod]);
					} else {
						$controller = new $controllerClass();
						// call the callback
						call_user_func([$controller, $controllerMethod]);
					}
					
				} else {
					http_response_code(405);
					echo json_encode(['error' => 'Method not allowed']);
				}
			} else {
				http_response_code(405);
				echo json_encode(['error' => 'Method not allowed']);
			}
		} else {
			http_response_code(404);
			echo json_encode(['error' => 'Not found']);
		}
	}

	protected function removeQueryString( $url ) {
		// Parse the URL into its components
		$parsedUrl = parse_url($url);
	
		// Check if the 'path' key exists in the parsed URL
		if (isset($parsedUrl['path'])) {
			// If it does, return the path
			return $parsedUrl['path'];
		} else {
			// If it doesn't, return the original URL
			return $url;
		}
	}

	protected function setResponseHeaders() {
		header('Content-Type: application/json');
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

		// If the request method is OPTIONS, just exit - This will allow CORS to work on the preflight request
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			header('HTTP/1.1 200 OK');
  			die();
		}
	}
}
