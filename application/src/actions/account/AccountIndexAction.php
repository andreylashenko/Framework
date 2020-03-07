<?php
namespace application\src\actions\account;

class AccountIndexAction
{
    public function actionIndex(string $name, int $age) {

        return json_encode(["res" => [$name, $age]]);
    }
}
