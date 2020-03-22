<?php
namespace application\core\database;

use application\core\App;
use RedBeanPHP\R;

class DbConnection
{
    public function __construct()
    {
        $db = App::getConfig('db');
        R::setup('mysql:host=' . $db['host']. ';dbname=' . $db['dbname'], $db['user'], $db['password']);
        var_dump(R::testConnection());die;
    }
}
