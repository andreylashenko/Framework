<?php
namespace application\core\di;

use application\common\dependencies\DependenciesBootstrap;
use application\core\database\DbConnection;
use ReflectionClass;

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
                    Singleton::setInstance($key, self::loadConstructArgs($value));
                }
            }
        }
    }

    public static function loadConstructArgs(string $class) {

        $reflectionClass = new ReflectionClass($class);
        $constructor = $reflectionClass->getConstructor();
        $constructorArgs = [];

        if($constructor) {
            foreach ($constructor->getParameters() as $parameter) {
                $constructorArgs[] = Singleton::getInstances($parameter->getClass()->getName());
            }
        }

        return $reflectionClass->newInstanceArgs($constructorArgs);
    }
}
