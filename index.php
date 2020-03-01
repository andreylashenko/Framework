<?php

require 'application/lib/Dev.php';
use application\core\Router;



header('Content-Type: application/json');
spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class.'.php');

    if (file_exists($class)) {
        require $class;
    }
});

(new Router())->run();

