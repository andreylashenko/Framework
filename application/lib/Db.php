<?php

namespace application\lib;
use PDO;

class Db
{

    public $connection;

    public function __construct()
    {
        $config = require 'application/config/db.php';
        $this->connection = new PDO('mysql:host='.$config['host'].';dbname='.$config['dbname'].';charset=utf8', $config['user'], $config['password']);
    }

    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        if(!empty($params)) {
            foreach ($params as $key => $param) {
                $stmt->bindValue(":". $key, $param);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function row($sql, $params = []) {
        $result = $this->query($sql, $params);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($sql, $params = []) {
        $result = $this->query($sql, $params);

        return $result->rowCount();
    }

    public function create($sql, $params = []) {
        $this->query($sql, $params);
    }
}
