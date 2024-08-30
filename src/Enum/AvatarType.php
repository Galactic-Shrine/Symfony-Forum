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
 * Enumération représentant les différents types d'avatar disponibles.
 * 
 * Cette énumération fournit des types d'avatar que les utilisateurs peuvent choisir, tels que les avatars générés
 * par Gravatar, les avatars téléversés par les utilisateurs, et les avatars générés par l'application.
 * Elle implémente l'interface TranslatableInterface pour permettre la traduction
 * des valeurs en différentes langues via le service Translator.
 */
enum AvatarType: string implements TranslatableInterface {

    /**
     * Type d'avatar Gravatar.
     */
    case GrAvatar = 'GrAvatar';

    /**
     * Type d'avatar téléversable par l'utilisateur.
     */
    case Uploadable = 'Uploadable';

    /**
     * Type d'avatar généré par l'application.
     */
    case Generated = 'Generated';

    /**
     * Retourne la traduction de l'énumération en fonction de la locale spécifiée.
     * 
     * Utilise le service TranslatorInterface pour traduire le nom du type d'avatar
     * en fonction de la locale fournie. Si aucune locale n'est spécifiée, la locale
     * par défaut du traducteur sera utilisée.
     * 
     * @param TranslatorInterface $translator Le service de traduction
     * @param string|null $locale La locale de traduction (optionnelle)
     * @return string La valeur traduite du type d'avatar
     */
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
            
            self::GrAvatar => $translator->trans('Text.Config.GrAvatar', domain: 'User', locale: $locale),
            self::Uploadable => $translator->trans('Text.Config.Uploadable', domain: 'User', locale: $locale),
            self::Generated => $translator->trans('Text.Config.Generated', domain: 'User', locale: $locale),
        };
    }
}
