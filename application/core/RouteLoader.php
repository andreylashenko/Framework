<?php

namespace application\core;

class RouteLoader
{
    private array $routes = [];

    public function __construct()
    {
        $routes = require 'application/config/routes.php';
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
