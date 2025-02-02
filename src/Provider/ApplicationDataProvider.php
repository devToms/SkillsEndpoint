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
        try {
            $options = $operation->getOptions();
            $readStatus = $options['read_status'] ?? false;

            $queryBuilder = $this->applicationRepository->findByReadStatusAndOrder(false);

            $result = $queryBuilder->getQuery()->getResult();

            $this->logger->info('Query result', ['data' => $result]);

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Błąd podczas wykonywania zapytania', ['exception' => $e]);
            throw new NotFoundHttpException('Dane nie zostały znalezione.');
        } 
    }
}