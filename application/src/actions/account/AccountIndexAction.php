<?php
namespace application\src\actions\account;

use application\src\domain\defaultEntity\repository\DefaultEntityRepositoryInterface;

class AccountIndexAction
{
    private DefaultEntityRepositoryInterface $defaultEntityRepository;

    public function __construct(DefaultEntityRepositoryInterface $defaultEntityRepository)
    {
        $this->defaultEntityRepository = $defaultEntityRepository;
    }
    public function actionIndex(string $name, int $age) {

        return json_encode(["res" => $this->defaultEntityRepository->test()]);
    }
}
