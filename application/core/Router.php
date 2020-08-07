<?php

namespace application\core;

use application\core\di\DependencyLoader;
use ReflectionMethod;
use ReflectionClass;

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
        $prefix = $this->route[0];
        $controller = isset($this->route[1]) ? $this->route[1] : null;
        $action = isset($this->route[2]) ? $this->route[2] : null;

        if(!in_array($controller, $this->routes['controller'])) {
           throw new ExceptionHandler(404, 'undefined controller '. $controller);
        }

        if (!in_array($action, $this->routes['action'])) {
            throw new ExceptionHandler(404, 'undefined action '. $action);
        }

        $class = 'application\\src\\controllers\\'. $prefix .'\\'. ucfirst($controller).'Controller';
        $method = 'action'.ucfirst($action);

        if (class_exists($class)) {
            $methodArgs = [];
            $object = new ReflectionClass($class);
            $baseClass = DependencyLoader::loadBaseConstructArgs($class);
            $actions = $object->getMethod('actions')->invoke($baseClass);
            $httpType = call_user_func_array([$baseClass, 'behaviors'], [])['actions'];

            if (!isset($httpType[$action]) || strtoupper($httpType[$action]) !== $_SERVER['REQUEST_METHOD']) {
                throw new ExceptionHandler(500, 'method not allowed');
            }

            if(!isset($actions[$action])) {
                throw new ExceptionHandler(404, 'method not found');
            }

            $targetClass = $actions[$action];
            $targetMethod = new ReflectionMethod($targetClass, $method);
            $this->paramsParser();

            if($targetMethod->getParameters()) {
                $this->paramResolver->resolve($targetMethod, $this->params, $methodArgs);
            }

            $classObject =  DependencyLoader::loadConstructArgs($targetClass);

            echo call_user_func_array([$classObject, $method], $methodArgs);
        }
    }
    private function loadSystemClass() {

        $controller = isset($this->route[0]) ? $this->route[0] : null;
        $action = isset($this->route[1]) ? $this->route[1] : 'index';

        $systemClass = 'application\\core\\system\\'. $controller . '\\' . ucfirst($controller).'Controller';
        $method = 'action'.ucfirst($action);

        if (class_exists($systemClass)) {
            $methodArgs = [];
            $object = new ReflectionClass($systemClass);
            $actions = $object->getMethod($action)->invoke(new $systemClass);

            $httpType = call_user_func_array([new $systemClass, 'behaviors'], [])['actions'];

            if (!isset($httpType[$action]) || strtoupper($httpType[$action]) !== $_SERVER['REQUEST_METHOD']) {
                throw new ExceptionHandler(500, 'method not allowed');
            }


          //  $targetClass = $actions[$action];

            $targetMethod = new ReflectionMethod($systemClass, $action);

            $this->paramsParser();

            if($targetMethod->getParameters()) {
                $this->paramResolver->resolve($targetMethod, $this->params, $methodArgs);
            }

            $classObject =  DependencyLoader::loadConstructArgs($systemClass);

            echo call_user_func_array([$classObject, $action], $methodArgs);
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
        if ($this->route[0] === 'v1') {
            $this->match();
        } else {

            $this->loadSystemClass();
        }
    }
}

