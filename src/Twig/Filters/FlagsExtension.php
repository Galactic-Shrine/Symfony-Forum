<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Twig\Filters;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Classe FlagsExtension
 * Cette classe définit des filtres Twig personnalisés pour convertir les codes ISO en émojis de drapeau.
 */
class FlagsExtension extends AbstractExtension {

    /**
     * Retourne un tableau de filtres Twig personnalisés.
     *
     * @return array Un tableau d'objets TwigFilter.
     */
    public function getFilters(): array {

        return [
            // Définition de deux filtres Twig 'Flags' et 'flags' qui appellent la méthode isoToEmoji.
            new TwigFilter(name: 'Flags', callable: [$this, 'isoToEmoji'], options: ['is_safe' => ['html']]),
            new TwigFilter(name: 'flags', callable: [$this, 'isoToEmoji'], options: ['is_safe' => ['html']])
        ];
    }

    /**
     * Convertit un code ISO (par exemple "US") en émoji de drapeau correspondant.
     *
     * @param string $code Le code ISO du pays.
     * @return string L'émoji de drapeau correspondant.
     * 
     * @copyright Copyright (c) Grafikart
     * @license https://opensource.org/licenses/MIT MIT License
     */
    function isoToEmoji(string $code): string {

        // Utilise array_map pour convertir chaque lettre du code ISO en émoji de drapeau.
        return implode(
            '', 
            array_map(
                // Convertit chaque caractère en émoji de drapeau en utilisant sa valeur Unicode.
                fn ($letter) => mb_chr(ord(character: $letter) % 32 + 0x1F1E5), 
                str_split(string: $code)
            )
        );
    }
}