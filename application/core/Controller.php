<?php


namespace application\core;


use application\infrastructure\Resolver;
use ReflectionMethod;

abstract class Controller
{
    public $route;
    public $model;
    public $resolver;

    const API_KEY = 'FeqIlRFOGaRhFFNS5wPiSmN1lDu4l46X';

    /**
     *
     * @TODO запилить обработку DTO
     * Controller constructor.
     * @param $route
     */
    public function __construct($route)
    {
        $this->route = $route;
        $this->resolver = new Resolver();
        //$this->beforeAction();
        $this->model = $this->loadModel($route['controller']);
    }

    public function loadModel($name) {
        $path = 'application\models\\'.ucfirst($name);
        if(class_exists($path)) {
            return new $path;
        }
    }

    private function beforeAction() {

        $method = new ReflectionMethod(get_class($this), $this->route['action'].'Action');
        $content = file_get_contents("php://input");

        return $this->resolver->getArguments($method, $content);
    }
}
