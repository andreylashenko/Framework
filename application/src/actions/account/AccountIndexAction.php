<?php
namespace application\src\actions\account;

use application\src\domain\defaultEntity\entity\DefaultEntity;
use application\src\domain\defaultEntity\repository\DefaultEntityRepositoryInterface;
use application\src\domain\defaultEntity\service\DefaultEntityServiceInterface;

class AccountIndexAction
{
    private DefaultEntityRepositoryInterface $defaultEntityRepository;
    private DefaultEntityServiceInterface $defaultEntityService;

    public function __construct(
        DefaultEntityRepositoryInterface $defaultEntityRepository,
        DefaultEntityServiceInterface $defaultEntityService
    ) {
        $this->defaultEntityRepository = $defaultEntityRepository;
        $this->defaultEntityService = $defaultEntityService;
    }

    public function actionIndex(string $name, int $age) {

        $this->defaultEntityService->save(new DefaultEntity('Ivan', 23));
        return json_encode(["res" => $this->defaultEntityRepository->test()]);
    }
}
