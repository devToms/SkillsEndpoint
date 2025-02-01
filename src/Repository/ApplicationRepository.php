<?php

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class ApplicationRepository extends ServiceEntityRepository implements ApplicationRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function findByReadStatusAndOrder(bool $readStatus, array $orderBy = []): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.isRead = :readStatus')
            ->setParameter('readStatus', $readStatus);

        if (empty($orderBy)) {
            $orderBy = ['id' => 'ASC']; 
        }

        foreach ($orderBy as $field => $direction) {
            if (in_array($field, ['id', 'firstName', 'lastName', 'email', 'phoneNumber', 'expectedSalary', 'position', 'level', 'isRead'])) {
                $queryBuilder->addOrderBy('a.' . $field, $direction);
            }
        }
      
        return $queryBuilder;
    }
}
