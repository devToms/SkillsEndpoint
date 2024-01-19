<?php

namespace App\EventListener;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Application;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplicationReadListener implements EventSubscriberInterface
{
    public function __construct(
        private EntityManagerInterface $manager,
        private ApplicationRepository $applicationRepository
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        $id = $request->attributes->get('id');

        if (!$id || !$request->isMethod(Request::METHOD_GET)) {
            return;
        }

        $application = $this->applicationRepository->findOneBy([
          'id' => $id,
          'isRead' => 'new',
        ]);

        if ($application) {
          
           $application->setIsRead('read');

           $this->manager->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => ['onKernelRequest', EventPriorities::PRE_READ],
        ];
    }
}
