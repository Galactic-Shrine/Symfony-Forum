<?php

namespace App\EventListener;

use App\Service\UserLocationService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;

class UserLocationListener
{
    private Security $security;
    private UserLocationService $userLocationService;

    public function __construct(Security $security, UserLocationService $userLocationService)
    {
        $this->security = $security;
        $this->userLocationService = $userLocationService;
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $user = $this->security->getUser();

        if ($user) {
            $controller = get_class($event->getController()[0]);
            $action = $event->getController()[1];
            $this->userLocationService->updateLocation($user, $controller, $action);
        }
    }
}