<?php

namespace application\core\system\registration;

use application\core\system\database\DbConnection;

class RegistrationController
{
    private DbConnection $dbConnection;

    public function __construct(DbConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function index($login, $password)
    {
        // prepare statement for insert
        $sql = "INSERT INTO users(login,password,created_on) VALUES(:login,:password,:created_on)";
        $stmt = $this->dbConnection->getConn()->prepare($sql);

        // pass values to the statement
        $stmt->bindValue(':login', $login);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':created_on', date_format(date_create(), 'Y-m-d H:i:s-m'));

        // execute the insert statement
        $stmt->execute();

        $sql = "INSERT INTO users_token(user_id,token,last_login) VALUES(:user_id,:token,:last_login)";
        $stmt = $this->dbConnection->getConn()->prepare($sql);

        // pass values to the statement
        $stmt->bindValue(':user_id', $this->dbConnection->getConn()->lastInsertId());
        $stmt->bindValue(':token', md5($login.$password));
        $stmt->bindValue(':last_login', date_format(date_create(), 'Y-m-d H:i:s-m'));
        // execute the insert statement
        $stmt->execute();

        // generated id
        return $this->dbConnection->getConn()->lastInsertId('stocks_id_seq');
    }
}