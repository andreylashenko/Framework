<?php
namespace application\core;

class App
{
    public static function getRouter() {
        return require_once "./application/config/routes.php";
    }

    public static function getConfig() {
        return require_once "./application/config/config.php";
    }
}
