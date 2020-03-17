<?php
namespace application\src\domain\defaultEntity\service;

use application\core\database\DbConnection;
use application\src\domain\defaultEntity\entity\DefaultEntity;

class DefaultEntityService implements DefaultEntityServiceInterface
{
    private $db;
    public function __construct(DbConnection $db)
    {
        $this->db = $db;
    }

    public function save(DefaultEntity $defaultEntity)
    {
        die('asdasdasadasdad');
        $this->db->save($defaultEntity);
    }
}
