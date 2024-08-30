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
 * Enumération représentant les différents styles d'avatar.
 * 
 * Cette énumération fournit des styles d'avatar que les utilisateurs peuvent choisir.
 * Elle implémente l'interface TranslatableInterface pour permettre la traduction
 * des valeurs en différentes langues via le service Translator.
 */
enum AvatarStyle: int implements TranslatableInterface {

    /**
     * Style d'avatar carré.
     */
    case Square = 0;

    /**
     * Style d'avatar rectangulaire.
     */
    case Rectangle = 1;

    /**
     * Style d'avatar rond.
     */
    case Rounded = 2;

    /**
     * Style d'avatar octogonal.
     */
    case Octagonal = 3;

    /**
     * Retourne la traduction de l'énumération en fonction de la locale spécifiée.
     * 
     * Utilise le service TranslatorInterface pour traduire le nom du style d'avatar
     * en fonction de la locale fournie. Si aucune locale n'est spécifiée, la locale
     * par défaut du traducteur sera utilisée.
     * 
     * @param TranslatorInterface $translator Le service de traduction
     * @param string|null $locale La locale de traduction (optionnelle)
     * @return string La valeur traduite du style d'avatar
     */
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
            
            self::Square => $translator->trans('Text.Config.Square', domain: 'User', locale: $locale),
            self::Rectangle => $translator->trans('Text.Config.Rectangle', domain: 'User', locale: $locale),
            self::Rounded => $translator->trans('Text.Config.Rounded', domain: 'User', locale: $locale),
            self::Octagonal => $translator->trans('Text.Config.Octagonal', domain: 'User', locale: $locale),
        };
    }
}
