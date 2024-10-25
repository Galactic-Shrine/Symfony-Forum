<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Abonné à l'événement de requête du noyau pour gérer la langue de l'utilisateur.
 * 
 * Ce subscriber définit la langue de la requête en fonction du paramètre d'URL ou de la session.
 */
class LocaleSubscriber implements EventSubscriberInterface {

    /**
     * Langue par défaut à utiliser si aucune langue n'est spécifiée
     * @var string
     */
    private string $defaultLocale;

    /**
     * Constructeur du subscriber.
     * 
     * @param string $defaultLocale La langue par défaut à utiliser si aucune autre langue n'est spécifiée.
     */
    public function __construct(string $defaultLocale = 'en') {

        $this->defaultLocale = $defaultLocale;
    }

    /**
     * Retourne un tableau des événements auxquels ce subscriber est abonné.
     * 
     * @return array Le tableau des événements et des méthodes associées.
     */
    public static function getSubscribedEvents(): array {
        
        return [
            // Définit l'événement REQUEST avec une priorité élevée
            KernelEvents::REQUEST => [['onKernelRequestEvent', 20]],
        ];
    }

    /**
     * Méthode appelée lors de l'événement de requête.
     * 
     * @param RequestEvent $event L'événement de requête contenant les détails de la requête HTTP.
     */
    public function onKernelRequestEvent(RequestEvent $event): void {

        // Obtient l'objet requête depuis l'événement
        $request = $event->getRequest();

        // Si la requête n'a pas de session précédente, on ne peut pas récupérer la langue de la session
        if (!$request->hasPreviousSession()) {

            return;
        }

        // Vérifie si la langue est passée en paramètre de l'URL
        if ($locale = $request->query->get(key: '_locale')) {

            // Définit la langue de la requête à partir du paramètre d'URL
            $request->setLocale(locale: $locale);
        } elseif ($locale = $request->cookies->get('_locale')) {

            //Sinon, vérifie si la langue est stockée dans un cookie
            $request->setLocale($locale);
        } else {

            // Sinon, utilise la langue stockée dans la session ou la langue par défaut
            $request->setLocale(
                locale: $request->getSession()->get(
                    name: '_locale', 
                    default: $this->defaultLocale
                )
            );
        }
    }
}
