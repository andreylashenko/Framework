<?php

namespace application\src\controllers\v1;

use application\common\controllers\Controller;
use application\core\system\database\DbConnection;
use application\src\actions\account\AccountIndexAction;

class AccountController extends Controller
{
    public function behaviors()
    {
        return [
            'actions' => [
                'index' => 'get'
            ],
        ];
    }

    public function actions() {
        return [
            'index' => AccountIndexAction::class
        ];
    }
}
