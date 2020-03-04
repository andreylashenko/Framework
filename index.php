<?php

require 'application/lib/Dev.php';
use application\core\Router;
use application\core\di\Dependecy;


header('Content-Type: application/json');
spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class.'.php');

    if (file_exists($class)) {
        require $class;
    }
});

Dependecy::bootstrap();
(new Router())->run();

