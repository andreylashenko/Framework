<?php

namespace application\core\system\database;

use application\core\App;
use PDO;
use PDOException;

class DbConnection
{
    private $conn;

    public function __construct()
    {
        $db = App::getConfig('db');
        $dsn = 'pgsql:host=' . $db['host']. ';dbname=' . $db['dbname']. ';user=' . $db['user']. ';password=' . $db['password'];
        try{
            // create a PostgreSQL database connection
            $this->conn = new PDO($dsn);

            // display a message if connected to the PostgreSQL successfully
            if($this->conn){
                echo "Connected to the <strong>".$db['host']."</strong> database successfully!";
            }
        }catch (PDOException $e){
            // report error message
            echo $e->getMessage();
        }
    }
    public function checkToken($token) {

        $sql = "SELECT token FROM users_token WHERE token = :token";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();

        return $stmt->fetchColumn();
    }
    /**
     * @return PDO
     */
    public function getConn(): PDO
    {
        return $this->conn;
    }

}
