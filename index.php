<?php
use application\core\Router;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header('Content-Type: application/json');
ini_set("memory_limit", "800M");
ini_set('max_execution_time', 0);
set_time_limit(0);

spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if(file_exists($path)) {
        require $path;
    }
});

if(file_exists('application/vendor/autoload.php')) {
    require 'application/vendor/autoload.php';
}

$router = new Router();
$router->run();



