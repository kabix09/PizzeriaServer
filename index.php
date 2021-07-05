<?php
declare(strict_types=1);

require_once "vendor/autoload.php";

use Pizzeria\Web\Response;
use Pizzeria\Web\Server;
use Pizzeria\Route\Route;

ini_set("log_errors", "1");
ini_set("error_log", \Pizzeria\Logger\PizzeriaLogger::PATH_TO_LOGS_FILE);

$router = new Route();
$router->addRoute('api/pizza');
$router->addRoute('api/ingredient');
$router->addRoute('api/sauce');
$router->addRoute('api/order');

if(!isset($_GET['api'])) {
    Server::setHeader(
        Response::HEADER_CONTENT_TYPE,
        Response::CONTENT_TYPE_JSON,
        true,
        (int)Response::STATUS_200
    );
    return printf(json_encode(["message" => "Welcome in my pizzeria!!! :)"]));
}

if(!$router->isRouteExists($_GET['api'])) {
    Server::setHeader(
        Response::HEADER_CONTENT_TYPE,
        Response::CONTENT_TYPE_JSON,
        true,
        (int)Response::STATUS_501
    );
    return printf(json_encode(["message" => Response::STATUS_501 . " Error - this route doesn't exist"]));
}

$apiClassName = 'Pizzeria\Api\\' . ucfirst(
    substr($_GET['api'], 4)
);

$apiClass = new $apiClassName();

$server = new Server($apiClass);
$server->listen();