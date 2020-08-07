<?php

namespace application\common\controllers;

use application\core\ExceptionHandler;
use application\core\system\database\DbConnection;

abstract class Controller
{
    private DbConnection $dbConnection;

    public function __construct(DbConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
        $isAuthorized = $this->dbConnection->checkToken(getallheaders()['Authorization']);
        if(!$isAuthorized) {
            throw new ExceptionHandler(401, 'you are not authorized');
        }
    }
    
    abstract public function behaviors();

    abstract public function actions();
}
