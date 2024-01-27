<?php
require_once 'vendor/autoload.php';

use App\Controllers\RestApi\RestApiController;
use App\Utils\Request;

$controller = new RestApiController(new Request());
$controller->handleRequest();
