<?php

use application\core\Router;
use application\core\di\Dependency;

header('Content-Type: application/json');
spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class.'.php');

    if (file_exists($class)) {
        require $class;
    }
});
Dependency::loadSystemDependencies();
Dependency::bootstrap();
(new Router())->run();

