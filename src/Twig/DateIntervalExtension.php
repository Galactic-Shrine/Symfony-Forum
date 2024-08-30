<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Extension Twig pour ajouter des filtres et fonctions liés aux intervalles de dates.
 * 
 * Cette classe fournit des filtres et des fonctions Twig permettant de calculer
 * l'intervalle en années entre deux dates. Les filtres et fonctions définis ici
 * peuvent être utilisés dans les templates Twig pour formater et afficher des
 * informations sur la différence entre les dates.
 */
class DateIntervalExtension extends AbstractExtension {

    /**
     * Retourne la liste des filtres Twig disponibles dans cette extension.
     * 
     * @return TwigFilter[] Un tableau de filtres Twig
     */
    public function getFilters(): array {

        return [
            // Filtres pour calculer l'intervalle entre deux dates
            new TwigFilter(name: 'Interval', callable: [$this, 'DateInterval'], options: ['is_safe' => ['html']]),
            new TwigFilter(name: 'interval', callable: [$this, 'DateInterval'], options: ['is_safe' => ['html']]),
        ];
    }

    /**
     * Retourne la liste des fonctions Twig disponibles dans cette extension.
     * 
     * @return TwigFunction[] Un tableau de fonctions Twig
     */
    public function getFunctions(): array {

        return [
            // Fonctions pour calculer l'intervalle entre deux dates
            new TwigFunction(name: 'Interval', callable: [$this, 'DateInterval']),
            new TwigFunction(name: 'interval', callable: [$this, 'DateInterval']),
        ];
    }

    /**
     * Calcule l'intervalle en années entre deux dates.
     * 
     * @param string $Origin La date d'origine au format 'Y-m-d'
     * @param string $Target La date cible au format 'Y-m-d', par défaut 'now'
     * @return int Le nombre d'années entre les deux dates
     */
    public function DateInterval(string $Origin, string $Target = "now"): int {

        // Créer un objet DateTimeImmutable pour la date d'origine
        $origin = new \DateTimeImmutable(datetime: $Origin);
        // Créer un objet DateTimeImmutable pour la date cible
        $target = new \DateTimeImmutable(datetime: $Target);
        
        // Calculer la différence en années entre les deux dates et la retourner
        return $origin->diff(targetObject: $target)->y; //->format("m/d/Y H:i")
    }
}
