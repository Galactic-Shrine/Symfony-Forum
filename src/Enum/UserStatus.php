<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Enumération représentant les différents statuts d'utilisateur.
 * 
 * Cette énumération définit les statuts possibles d'un utilisateur dans l'application,
 * comme en ligne, absent, occupé, invisible et hors ligne. Elle implémente l'interface
 * TranslatableInterface pour permettre la traduction des valeurs en différentes langues
 * via le service Translator.
 */
enum UserStatus: string implements TranslatableInterface {

    /**
     * Utilisateur en ligne.
     */
    case ONLINE = 'Online';

    /**
     * Utilisateur absent.
     */
    case ABSENT = 'Absent';

    /**
     * Utilisateur occupé.
     */
    case OCCUPIED = 'Occupied';

    /**
     * Utilisateur invisible.
     */
    case INVISIBLE = 'Invisible';

    /**
     * Utilisateur hors ligne.
     */
    case OFFLINE = 'Offline';

    /**
     * Retourne la traduction de l'énumération en fonction de la locale spécifiée.
     * 
     * Utilise le service TranslatorInterface pour traduire le statut de l'utilisateur
     * en fonction de la locale fournie. Si aucune locale n'est spécifiée, la locale
     * par défaut du traducteur sera utilisée.
     * 
     * @param TranslatorInterface $translator Le service de traduction
     * @param string|null $locale La locale de traduction (optionnelle)
     * @return string La valeur traduite du statut d'utilisateur
     */
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
            
            self::ONLINE => $translator->trans('Status.Online', domain: 'User', locale: $locale),
            self::ABSENT => $translator->trans('Status.Absent', domain: 'User', locale: $locale),
            self::OCCUPIED => $translator->trans('Status.Occupied', domain: 'User', locale: $locale),
            self::INVISIBLE => $translator->trans('Status.Invisible', domain: 'User', locale: $locale),
            self::OFFLINE => $translator->trans('Status.Offline', domain: 'User', locale: $locale),
        };
    }
}
