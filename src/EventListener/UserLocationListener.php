<?php

namespace App\EventListener;

use App\Service\UserLocationService;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Security\Core\Security;

class UserLocationListener {

    private $Security;
    private $UserLocationService;

    public function __construct(Security $security, UserLocationService $userLocationService) {
        
        $this->Security = $security;
        $this->UserLocationService = $userLocationService;
    }

    public function onKernelController(ControllerEvent $Event) {

        $User = $this->Security->getUser();
        if ($User) {

            $Controller = get_class($Event->getController()[0]);
            $Action = $Event->getController()[1];
            $this->UserLocationService->updateLocation($User, $Controller, $Action);
        }
    }
}
