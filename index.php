<?php
declare(strict_types=1);

use Pizzeria\Connection\DbConnection;
use Pizzeria\Web\Response;
use Pizzeria\Web\Server;
use Pizzeria\Route\Route;

require_once realpath("vendor/autoload.php");

$router = new Route();
$router->addRoute('api/pizza');
$router->addRoute('api/ingredient');
$router->addRoute('api/sauce');
$router->addRoute('api/order');

// localhost:8080/api/{pizza, ingredients, sauce}
if(isset($_GET['api'])) {

    if(!$router->isRouteExists($_GET['api'])) {
        Server::setHeader(
            Response::HEADER_CONTENT_TYPE,
            Response::CONTENT_TYPE_JSON,
            true,
            (int)Response::STATUS_501
        );
        printf(json_encode(Response::STATUS_501 . " Error - this route doesn't exist"));
        die();
    }

    $apiClassName = 'Pizzeria\Api\\' . ucfirst(
        substr($_GET['api'], 4)
    );

    $apiCalss = new $apiClassName(new DbConnection());

    $server = new Server($apiCalss);
    $server->listen();
}
else
    printf("Hello world - server don't work");
