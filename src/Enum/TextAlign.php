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
 * Enumération représentant les différents alignements de texte.
 * 
 * Cette énumération définit les alignements de texte disponibles pour le contenu
 * dans l'éditeur. Elle implémente l'interface TranslatableInterface pour permettre
 * la traduction des valeurs en différentes langues via le service Translator.
 */
enum TextAlign: string implements TranslatableInterface {

    /**
     * Alignement du texte à gauche.
     */
    case Left = 'Left aligned';

    /**
     * Alignement du texte au centre.
     */
    case Center = 'Center aligned';

    /**
     * Alignement du texte à droite.
     */
    case Right = 'Right aligned';

    /**
     * Retourne la traduction de l'énumération en fonction de la locale spécifiée.
     * 
     * Utilise le service TranslatorInterface pour traduire l'alignement du texte
     * en fonction de la locale fournie. Si aucune locale n'est spécifiée, la locale
     * par défaut du traducteur sera utilisée.
     * 
     * @param TranslatorInterface $translator Le service de traduction
     * @param string|null $locale La locale de traduction (optionnelle)
     * @return string La valeur traduite de l'alignement du texte
     */
    public function trans(TranslatorInterface $translator, ?string $locale = null): string {

        return match ($this) {
            
            self::Left => $translator->trans('Text.Align.Left', domain: 'Editor', locale: $locale),
            self::Center => $translator->trans('Text.Align.Center', domain: 'Editor', locale: $locale),
            self::Right => $translator->trans('Text.Align.Right', domain: 'Editor', locale: $locale),
        };
    }
}
