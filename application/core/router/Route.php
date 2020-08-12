<?php

namespace application\core\router;

use application\core\ExceptionHandler;

class Route
{
    const PREFIX = 'v1';
    const BASE_CLIENT_PATH = 'application\\src\\controllers\\';
    const BASE_SYSTEM_PATH = 'application\\core\\system\\';

    private string $controller;
    private string $action;
    private string $prefix;
    private array $params;

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $route = $this->getRoute();

        if($route === '/') {
            throw new ExceptionHandler(404, 'route not found');
        }

        $this->setPrefix($route[0] ?? self::PREFIX);
        $this->setParams($this->parsedParams());

        if ($this->getPrefix() === self::PREFIX) {
            $this->setController($route[1] ?? 'default');
            $this->setAction($route[2] ?? 'index');
        } else {
            $this->setController($route[0] ?? 'default');
            $this->setAction($route[1] ?? 'index');
        }
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function setController(string $controller): void
    {

        $this->controller = $controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): void
    {
        $this->prefix = $prefix;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }

    private function getUriData()
    {
        $uri = $_SERVER['REQUEST_URI'];
        return explode('?', $uri);
    }

    public function getRoute()
    {
        $uriData = $this->getUriData();
        return explode('/', ltrim(array_shift($uriData), '/'));
    }

    private function parsedParams(): array
    {
        $uri_data = $this->getUriData();
        return isset($uri_data[1]) ? explode('&', $uri_data[1]) : [];
    }

    public function getUserClassPath()
    {
        return self::BASE_CLIENT_PATH . $this->getPrefix() .'\\'. ucfirst($this->controller).'Controller';
    }

    public function getSystemClassPath()
    {
        return self::BASE_SYSTEM_PATH . $this->controller .'\\'. ucfirst($this->controller).'Controller';
    }
}