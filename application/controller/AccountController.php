<?php
namespace application\controller;

class AccountController
{

    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger;
    }

    public function actionIndex(string $name, int $age) {
        return json_encode(["res" => [$name, $age]]);
    }
}
