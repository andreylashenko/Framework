<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

use application\core\router\Router;
use application\core\di\DependencyLoader;

header('Content-Type: application/json');
spl_autoload_register(function($class) {
    $class = str_replace('\\', '/', $class.'.php');

    if (file_exists($class)) {
        require $class;
    }
});

require_once './vendor/autoload.php';

DependencyLoader::run();
(new Router())->run();

