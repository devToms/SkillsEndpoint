<?php

namespace App\Controller;

use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReadApplicationsController extends AbstractController
{
    public function __construct(
        private ApplicationRepository $applicationRepository
    ) {
    }

    public function __invoke(Request $request): array
    {
        $orderBy = $request->query->all();

        $application = $this->applicationRepository->findByReadStatusAndOrder(true, $orderBy);

        return $application;
    }
}
