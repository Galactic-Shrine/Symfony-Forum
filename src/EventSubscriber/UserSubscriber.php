<?php

namespace App\EventSubscriber;

use App\Enum\UserStatus;
use App\Service\UserPresenceService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

class UserSubscriber implements EventSubscriberInterface {
    private UserPresenceService $userPresenceService;

    public function __construct(UserPresenceService $userPresenceService) {

        $this->userPresenceService = $userPresenceService;
    }

    public static function getSubscribedEvents(): array {

        return [
            InteractiveLoginEvent::class => 'onUserLogin',
            LogoutEvent::class => 'onUserLogout',
        ];
    }

    public function onUserLogin(InteractiveLoginEvent $event): void {

        $user = $event->getAuthenticationToken()->getUser();

        if ($user) {
            
            $this->userPresenceService->updateStatus($user, UserStatus::ONLINE);
        }
    }

    public function onUserLogout(LogoutEvent $event): void {

        $user = $event->getToken()->getUser();

        if ($user) {
            
            $this->userPresenceService->updateStatus($user, UserStatus::OFFLINE);
        }
    }
}