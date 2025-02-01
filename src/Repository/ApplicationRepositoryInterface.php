<?php

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface ApplicationRepositoryInterface
{
    public function findByReadStatusAndOrder(bool $readStatus, array $orderBy = []): QueryBuilder;
}
