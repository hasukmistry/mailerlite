<?php
require_once 'vendor/autoload.php';

use App\Controllers\RestApi\RestApiController;
use App\Utils\JsonResponse;
use App\Utils\Request;
use App\Utils\Routes;

// Create a new request instance
$request = new Request();

// configure the routes
$request->setRoutes(
    Routes::getRoutes()
);

// Set the response
$request->setResponse(
    new JsonResponse()
);

// Create a new rest api controller instance
$controller = new RestApiController();

// Handle the rest api request
$controller->handleRequest($request);
