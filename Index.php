<?php

require_once './src/config/db.php';
require_once './src/routes/Router.php';

header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-type: application/json; charset=UTF-8");

$router = new Router();

require_once './public/indexModels.php';

$requestedPath = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$pathItems = explode("/", $requestedPath);

$requestedPath = "/" . (isset($pathItems[2]) ? $pathItems[3] : "") . 
                 (isset($pathItems[4]) && $pathItems[4] ? "/" . $pathItems[4] : "");

$router->dispatch($requestedPath);