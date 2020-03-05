<?php
namespace application\core;

class App
{
    public static function getRouter(): array {
        return require_once "./application/config/routes.php";
    }

    public static function getConfig(string $config): array {
        $configs = require_once "./application/config/config.php";

        if (!isset($configs[$config])) {
            throw new ExceptionHandler(500, 'config '. $config . ' doesn\'t exist');
        }

        return $configs[$config];
    }
}
