<?php
require_once 'vendor/autoload.php';

$controller = new App\Controllers\RestApi\RestApi();
$controller->handleRequest();
