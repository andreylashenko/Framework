<?php
namespace application\core\di;

use application\controller\Logger;
use application\core\db\DbConnection;

class Dependency
{
    public static function bootstrap() {
        DiContainer::setInstance(Logger::class, new Logger());
    }

    public static function loadSystemDependencies() {
        DiContainer::setInstance(DbConnection::class, new DbConnection());
    }
}
