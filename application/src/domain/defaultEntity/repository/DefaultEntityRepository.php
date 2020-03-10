<?php
namespace application\src\domain\defaultEntity\repository;

class DefaultEntityRepository implements DefaultEntityRepositoryInterface
{

    public function test(): string
    {
        return 'hello';
    }
}
