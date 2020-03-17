<?php


namespace application\src\domain\defaultEntity\service;


use application\src\domain\defaultEntity\entity\DefaultEntity;

interface DefaultEntityServiceInterface
{
    public function save(DefaultEntity $defaultEntity);
}
