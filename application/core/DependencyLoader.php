<?php
namespace application\core;

use ReflectionClass;

class DependencyLoader
{
    public static function loadConstructArgs(string $class) {

        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();

        $constructorArgs = [];
        foreach ($constructor->getParameters() as $parameter) {
            $constructorArgs[] = DiContainer::getInstances($parameter->getClass()->getName());
        }

        return $reflectionClass->newInstanceArgs($constructorArgs);
    }
}
