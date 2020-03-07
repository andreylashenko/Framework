<?php
namespace application\src\controllers\v1;

use application\src\actions\account\AccountIndexAction;

class AccountController
{
    public function actions() {
        return [
            'index' => AccountIndexAction::class
        ];
    }
}
