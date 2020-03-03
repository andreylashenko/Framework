<?php

namespace application\core;

use ReflectionMethod;

class Router
{
    protected array $route = [];
    protected array $params = [];
    protected array $routes = [];
    protected ParamsResolver $paramResolver;

    public function __construct()
    {
       $this->routes = (new RouteLoader())->getRoutes();
       $this->paramResolver = new ParamsResolver();
    }

    private function paramsParser() {
        if ($this->params) {
            foreach ($this->params as $cnt => $param) {
                list($key, $value) = explode('=', $param);
                unset($this->params[$cnt]);
                $this->params[$key] = $value;
            }
        }

        $body_params = file_get_contents('php://input');
        if ($body_params) {
            $this->params['body'] = json_decode($body_params);
        }
    }

    private function match() {
        $controller = isset($this->route[0]) ? $this->route[0] : null;
        $action = isset($this->route[1]) ? $this->route[1] : null;

        if(!in_array($controller, $this->routes['controller'])) {
           throw new ExceptionHandler(404, 'undefined controller '. $controller);
        }

        if (!in_array($action, $this->routes['action'])) {
            throw new ExceptionHandler(404, 'undefined action '. $action);
        }

        $class = 'application\\controller\\'.ucfirst($controller).'Controller';
        $method = 'action'.ucfirst($action);

        if (class_exists($class)) {
            $methodArgs = [];
            $object = new ReflectionMethod($class, $method);
            $this->paramsParser();
            if($object->getParameters()) {
                $this->paramResolver->resolve($object, $this->params, $methodArgs);

            }

            $classObject =  DependencyLoader::loadConstructArgs($class);

            echo call_user_func_array([$classObject, $method], $methodArgs);
        }
    }

    public function run() {
        $uri = $_SERVER['REQUEST_URI'];

        $uri_data = explode('?', $uri);
        $route = $uri_data[0];
        if($route === '/') {
            throw new ExceptionHandler(404, 'route not found');
        }

        $this->route = explode('/', ltrim($route, '/'));
        $this->params = isset($uri_data[1]) ? explode('&', $uri_data[1]) : [];

        $this->match();
    }
}

