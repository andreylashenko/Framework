<?php
namespace application\core\db;

use application\core\App;
use PDO;

class DbConnection
{
    private $dbh;

    public function __construct()
    {
        $db = App::getConfig('db');
        $dbh = new PDO('mysql:host=' . $db['host']. ';dbname=' . $db['dbname'], $db['user'], $db['password']);
    }
}
