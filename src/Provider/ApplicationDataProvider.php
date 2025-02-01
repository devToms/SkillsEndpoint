<?php

namespace App\Provider;

use Psr\Log\LoggerInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Repository\ApplicationRepositoryInterface;

final class ApplicationDataProvider implements ProviderInterface
{
    public function __construct(
        private readonly ApplicationRepositoryInterface $applicationRepository,
        private readonly LoggerInterface $logger

    ) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): iterable
    {
        $options = $operation->getOptions();
        $readStatus = $options['read_status'] ?? false;
        $queryBuilder = $this->applicationRepository->findByReadStatusAndOrder(false);
        $this->logger->info('Query Result', ['data' => $queryBuilder->getQuery()->getResult()]);
        return $queryBuilder->getQuery()->getResult();
    }
}