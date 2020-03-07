<?php
namespace application\controller;

use application\src\actions\account\AccountIndexAction;

class AccountController
{
    public function actions() {
        return [
            'index' => AccountIndexAction::class
        ];
    }
}
