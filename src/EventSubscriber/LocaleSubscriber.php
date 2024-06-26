<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface {

    // Langue par défaut
    private string $defaultLocale;

    public function __construct(string $defaultLocale = 'en') {

        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequestEvent(RequestEvent $event): void {

        $request = $event->getRequest();

        // Si la requête n'a pas de session précédente, on ne peut pas récupérer la langue de la session
        if (!$request->hasPreviousSession()) {

            return;
        }

        // On vérifie si la langue est passée en paramètre de l'URL
        if ($locale = $request->query->get('_locale')) {

            $request->setLocale($locale);
        }
        else {

            // Sinon on utilise celle de la session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents(): array {
        
        return [
    
            // On définir une priorité élevée
            KernelEvents::REQUEST => [['onKernelRequestEvent', 20]],
        ];
    }
}
