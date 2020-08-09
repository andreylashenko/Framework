<?php

namespace application\core\di;

use application\common\dependencies\DependenciesBootstrap;
use application\core\system\database\DbConnection;

class DependencyLoader
{
    public static function run() {
        self::loadSystemDependencies();
        DependenciesBootstrap::bootstrap();
        self::loadDomainDependencies();
    }

    /**
     * Load system dependencies
     */
    public static function loadSystemDependencies() {
        Singleton::setInstance(DbConnection::class, new DbConnection());
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
                    Singleton::setInstance($key, ConstructArgsLoader::loadConstructArgs($value));
                }
            }
        }
    }
}
