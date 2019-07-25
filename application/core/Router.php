<?php

namespace application\core;

use application\infrastructure\Resolver;
use application\infrastructure\Response;
use ReflectionMethod;

class Router {

    protected $routes = [];
    protected $params = [];

    public function __construct() {

        $arr = require 'application/config/routes.php';

        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    public function add($route, $params) {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    public function match() {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
            if(preg_match($route, $url, $match)){
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function run() {

        if(!$this->match()) {
            Response::json(null, "Wrong routing");
        }

        $path = 'application\controllers\\'.ucfirst($this->params['controller']).'Controller';

        if(!class_exists($path)) {
            Response::json(null, "Class not found");
        }

        if($_SERVER['REQUEST_METHOD'] !== strtoupper($this->params['method'])) {
            Response::json(null, "Method not allowed");
        }

        $action = $this->params['action']. 'Action';

        if(!method_exists($path, $action)) {
            Response::json(null, "Action not found");
        }

        $controller = new $path($this->params);
        $content = urldecode(file_get_contents("php://input"));

        $chunks = array_chunk(preg_split('/(=|&)/', $content), 2);
        $result = array_combine(array_column($chunks, 0), array_column($chunks, 1));

        if($content) {
            $method = new ReflectionMethod($controller, $action);
            $resolver = new Resolver();
            $data = $resolver->getArguments($method, $result["data"]);

            $controller->$action($result["api_key"], $data);
        } else {
            $controller->$action();
        }
    }
}
