<?php
use application\core\Router;

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
#header('Content-type: application/json; charset=utf-8');



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



