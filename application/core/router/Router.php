<?php

namespace application\core\router;

use application\core\ExceptionHandler;
use application\core\resolver\ParamsResolver;

class Router
{
    private RouteProcessing $routeProcessing;
    private ParamsResolver $paramResolver;
    private Route $route;
    private string $controller;
    private string $action;
    private array $params = [];
    private array $routes;


    public function __construct()
    {
       $this->routes = (new RouteLoader())->getRoutes();
       $this->paramResolver = new ParamsResolver();
       $this->routeProcessing = new RouteProcessing();
       $this->route = new Route();
    }

    private function loadUserClass()
    {
        if(!in_array($this->controller, $this->routes['controller'])) {
           throw new ExceptionHandler(404, 'undefined controller '. $this->controller);
        }

        if (!in_array($this->action, $this->routes['action'])) {
            throw new ExceptionHandler(404, 'undefined action '. $this->action);
        }

        $class = $this->route->getUserClassPath();

        if (class_exists($class)) {
            try {
                $this->routeProcessing->clientClassProcess($class, $this->action, $this->params);
            } catch (ExceptionHandler $e) {
                throw new ExceptionHandler(500, 'cannot process route');
            }
        }
    }

    private function loadSystemClass()
    {
        $class = $this->route->getSystemClassPath();

        if (class_exists($class)) {
            $this->routeProcessing->systemClassProcess($class, $this->action, $this->params);
        }
    }

    public function run()
    {
        $this->controller = $this->route->getController();
        $this->action = $this->route->getAction();
        $this->params = $this->route->getParams();

        if ($this->route->getPrefix() === Route::PREFIX) {
            $this->loadUserClass();
        } else {
            $this->loadSystemClass();
        }
    }
}

