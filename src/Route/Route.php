<?php
declare(strict_types=1);

namespace Pizzeria\Route;

class Route
{

    /**
     * @var array
     */
    private $routesList;

    public function __construct()
    {
        $this->routesList = array();
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routesList;
    }

    /**
     * @param array $routesList
     */
    public function setRoutesList(array $routesList): void
    {
        $this->routesList = $routesList;
    }

    /**
     * @param string $route
     */
    public function addRoute(string $route): void
    {
        $this->routesList[] = $route;
    }

    public function isRouteExists($route): bool
    {
        return in_array($route, $this->routesList, true);
    }
}