<?php

namespace application\core\router;

use application\core\di\ConstructArgsLoader;
use application\core\ExceptionHandler;
use application\core\resolver\ParamsResolver;
use ReflectionMethod;
use ReflectionClass;

class RouteProcessing
{
    protected ParamsResolver $paramResolver;

    public function __construct()
    {
        $this->paramResolver = new ParamsResolver();
    }

    public function clientClassProcess(string $class, string $action, $params)
    {
        $this->checkHttpType(ConstructArgsLoader::loadBaseConstructArgs($class), $action);
        $targetClass = $this->checkActionsClass($class, $action);
        $method = 'action'.ucfirst($action);

        $this->paramProcessing($targetClass, $method, $params);
    }

    public function systemClassProcess(string $class, string $action, $params)
    {
        $this->paramProcessing($class, $action, $params);
    }

    private function checkHttpType($class, $action)
    {
        $behaviors = call_user_func_array([$class, 'behaviors'], []);

        if (!isset($behaviors['actions'])) {
            throw new ExceptionHandler(500, 'cannot find method action in behaviors');
        }

        if (!isset($behaviors['actions'][$action]) || strtoupper($behaviors['actions'][$action]) !== $_SERVER['REQUEST_METHOD']) {
            throw new ExceptionHandler(500, 'method not allowed');
        }
    }

    private function checkActionsClass($class, $action)
    {
        $object = new ReflectionClass($class);
        $baseClass = ConstructArgsLoader::loadBaseConstructArgs($class);
        $actions = $object->getMethod('actions')->invoke($baseClass);

        if (!isset($actions[$action])) {
            throw new ExceptionHandler(404, 'method not found');
        }

        return $actions[$action];
    }

    private function paramProcessing($class, $method, $params)
    {
        $methodArgs = [];

        $targetMethod = new ReflectionMethod($class, $method);

        RouterParamsParser::parse($params);

        if ($targetMethod->getParameters()) {
            $this->paramResolver->resolve($targetMethod, $params, $methodArgs);
        }

        $classObject = ConstructArgsLoader::loadConstructArgs($class);

        echo call_user_func_array([$classObject, $method], $methodArgs);
    }
}