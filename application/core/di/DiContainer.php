<?php
namespace application\core\di;

use application\core\ExceptionHandler;

class DiContainer
{
    private static $instances = [];

    private function __construct(){}
    private function __clone() {}

    public static function setInstance($class, $definition) {

        if(!isset(self::$instances[$class])) {
            self::$instances[$class] = $definition;
        }
    }

    public static function getInstances($class) {

        if(isset(self::$instances[$class])) {
            return self::$instances[$class];
        }

        throw new ExceptionHandler(500, 'Cannot load class '. $class);
    }
}
