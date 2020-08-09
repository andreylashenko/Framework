<?php

namespace application\core\router;

use application\core\di\ConstructArgsLoader;
use application\core\ExceptionHandler;
use application\core\resolver\ParamsResolver;
use ReflectionMethod;

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

        $class = 'application\\src\\controllers\\'. $this->route->getPrefix() .'\\'. ucfirst($this->controller).'Controller';
        $method = 'action'.ucfirst($this->action);

        if (class_exists($class)) {
            try {
                $this->routeProcessing->process($class, $this->action, $method, $this->params);
            } catch (ExceptionHandler $e) {
                throw new ExceptionHandler(500, 'cannot process route');
            }
        }
    }

    private function loadSystemClass()
    {
        $systemClass = 'application\\core\\system\\'. $this->controller . '\\' . ucfirst($this->controller).'Controller';

        if (class_exists($systemClass)) {
            $methodArgs = [];

            $targetMethod = new ReflectionMethod($systemClass, $this->action);

            RouterParamsParser::parse($this->params);

            if($targetMethod->getParameters()) {
                $this->paramResolver->resolve($targetMethod, $this->params, $methodArgs);
            }

            $classObject = ConstructArgsLoader::loadConstructArgs($systemClass);

            echo call_user_func_array([$classObject, $this->action], $methodArgs);
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

