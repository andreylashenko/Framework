<?php

use application\src\domain\defaultEntity\repository\{DefaultEntityRepositoryInterface, DefaultEntityRepository};
use application\src\domain\defaultEntity\service\{DefaultEntityServiceInterface, DefaultEntityService};

return [
   DefaultEntityRepositoryInterface::class => DefaultEntityRepository::class,
   DefaultEntityServiceInterface::class => DefaultEntityService::class
];
