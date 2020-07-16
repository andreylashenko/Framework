<?php
namespace application\src\actions\account;

use application\src\domain\defaultEntity\dto\DefaultDto;
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

    public function actionIndex(DefaultDto $defaultDto) {

        return json_encode(["res" => $defaultDto]);
    }
}
