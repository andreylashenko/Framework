<?php
namespace application\core\di;

use application\core\database\DbConnection;

class Dependency
{
    public static function bootstrap() {
      //  DiContainer::setInstance(Logger::class, new Logger());
    }

    public static function loadSystemDependencies() {
        DiContainer::setInstance(DbConnection::class, new DbConnection());
    }
}
