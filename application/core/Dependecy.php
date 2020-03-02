<?php


namespace application\core;


use application\controller\Logger;

class Dependecy
{
    public static array $singletons = [];

    private function setSingleton($class, $definition) {
        self::$singletons[$class] = $definition;
    }

    public function bootstrap() {
        $container = new Dependecy();

        $container->setSingleton(Logger::class, new Logger());
    }


}
