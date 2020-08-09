<?php

namespace application\core\di;

use ReflectionClass;

class ConstructArgsLoader
{
    public static function loadConstructArgs(string $class)
    {
        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();
        $constructorArgs = [];

        if($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                $constructorArgs[] = Singleton::getInstances($parameter->getClass()->getName());
            }
        }

        return $reflectionClass->newInstanceArgs($constructorArgs);
    }

    public static function loadBaseConstructArgs(string $class)
    {
        $reflectionClass = new ReflectionClass($class);
        $constructorArgs = [];

        if ($parentClass = $reflectionClass->getParentClass()) {
            if($constructor = $parentClass->getConstructor()) {
                foreach ($constructor->getParameters() as $parameter) {
                    $constructorArgs[] = Singleton::getInstances($parameter->getClass()->getName());
                }
            }
        }

        return $reflectionClass->newInstanceArgs($constructorArgs);
    }
}