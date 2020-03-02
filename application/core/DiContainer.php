<?php


namespace application\core;


class DiContainer
{
    private static $instances = [];

    private function __construct(){}

    private function __clone() {}

    public static function getInstances() {
        $subclass = static::class;

        if(!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static;
        }

        return self::$instances[$subclass];
    }

}
