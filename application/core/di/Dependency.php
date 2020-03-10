<?php
namespace application\core\di;

use application\core\database\DbConnection;

class Dependency
{
    public static function run() {
        self::loadSystemDependencies();
        self::bootstrap();
        self::loadDomainDependencies();
    }

    /**
     * Load users dependencies
     */
    public static function bootstrap() {
      //  DiContainer::setInstance(Logger::class, new Logger());
    }

    /**
     * Load system dependencies
     */
    public static function loadSystemDependencies() {
        DiContainer::setInstance(DbConnection::class, new DbConnection());
    }

    /**
     * Load domain dependencies
     */
    public static function loadDomainDependencies() {
        $directory = 'application/src/dependencies/domain';
        $scannedDirectory = array_diff(scandir($directory), array('..', '.'));

        foreach ($scannedDirectory as $filename) {
            $file = $directory.'/'.$filename;
            if (is_file($file)) {
                $dependencies = require_once $file;
                foreach ($dependencies as $key => $value) {
                    DiContainer::setInstance($key, new $value);
                }
            }
        }
    }
}
