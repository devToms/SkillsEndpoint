<?php

namespace App\Controller;

use App\Entity\Application;
use App\Repository\ApplicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

class NewApplicationsController extends AbstractController
{
  public function __construct(
       private ApplicationRepository $applicationRepository
   ){}

   public function __invoke(Request $request): array
   {
       $orderBy = $request->query->all();

       $application = $this->applicationRepository->findByReadStatusAndOrder('new', $orderBy['order']);

       return $application;
   }
}
