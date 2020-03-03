<?php
namespace application\core;

use application\controller\Logger;

class Dependecy
{
    public static function bootstrap() {
        DiContainer::setInstance(Logger::class, new Logger());
    }
}
