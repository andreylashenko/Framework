<?php

namespace application\core\router;

use application\core\App;

class RouteLoader
{
    private array $routes = [];

    public function __construct()
    {
        $routes = App::getRouter();
        $this->load($routes);
    }

    private function load(array $routes) {
        foreach ($routes as $route) {
            $this->routes['controller'][] = $route['controller'];
            $this->routes['action'][] = $route['action'];
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
