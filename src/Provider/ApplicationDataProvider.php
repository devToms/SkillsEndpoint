<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ApplicationRepositoryInterface;

final class ApplicationDataProvider implements ProviderInterface
{
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository
    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $options = $operation->getOptions();
        $readStatus = $options['read_status'] ?? false;
        $queryBuilder = $this->applicationRepository->findByReadStatusAndOrder($readStatus);

        return $queryBuilder->getQuery()->getResult();
    }
}