<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\EventSubscriber;

use App\Enum\UserStatus;
use App\Service\UserPresenceService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

/**
 * Abonné aux événements de connexion et de déconnexion des utilisateurs.
 * 
 * Ce subscriber met à jour le statut de présence de l'utilisateur lors de sa connexion ou déconnexion.
 */
class UserSubscriber implements EventSubscriberInterface {

    /**
     * Service de gestion de la présence des utilisateurs.
     * 
     * @var UserPresenceService
     */
    private UserPresenceService $userPresenceService;

    /**
     * Constructeur du subscriber.
     * 
     * @param UserPresenceService $userPresenceService Service pour gérer la présence des utilisateurs.
     */
    public function __construct(UserPresenceService $userPresenceService) {

        $this->userPresenceService = $userPresenceService;
    }

    /**
     * Retourne un tableau des événements auxquels ce subscriber est abonné.
     * 
     * @return array Le tableau des événements et des méthodes associées.
     */
    public static function getSubscribedEvents(): array {

        return [
            InteractiveLoginEvent::class => 'onUserLogin',
            LogoutEvent::class => 'onUserLogout',
        ];
    }

    /**
     * Méthode appelée lors d'une connexion utilisateur.
     * 
     * @param InteractiveLoginEvent $event L'événement de connexion contenant des informations sur l'utilisateur connecté.
     */
    public function onUserLogin(InteractiveLoginEvent $event): void {

        // Obtient l'utilisateur depuis l'événement de connexion
        $user = $event->getAuthenticationToken()->getUser();

        if ($user) {

            // Met à jour le statut de l'utilisateur à "en ligne"
            $this->userPresenceService->updateStatus(User: $user, Status: UserStatus::ONLINE);
        }
    }

    /**
     * Méthode appelée lors d'une déconnexion utilisateur.
     * 
     * @param LogoutEvent $event L'événement de déconnexion contenant des informations sur l'utilisateur déconnecté.
     */
    public function onUserLogout(LogoutEvent $event): void {

        // Obtient l'utilisateur depuis l'événement de déconnexion
        $user = $event->getToken()->getUser();

        if ($user) {

            // Met à jour le statut de l'utilisateur à "hors ligne"
            $this->userPresenceService->updateStatus(User: $user, Status: UserStatus::OFFLINE);
        }
    }
}
