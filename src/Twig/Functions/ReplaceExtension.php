<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Twig\Functions;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension Twig pour ajouter des fonctions de remplacement de texte.
 * 
 * Cette classe fournit des fonctions Twig pour remplacer des sous-chaînes dans une chaîne
 * donnée. Les fonctions définies ici permettent de faire des remplacements de texte dans les
 * templates Twig en utilisant la méthode `str_replace`.
 */
class ReplaceExtension extends AbstractExtension {

    /**
     * Retourne la liste des fonctions Twig disponibles dans cette extension.
     * 
     * @return TwigFunction[] Un tableau de fonctions Twig
     */
    public function getFunctions(): array {

        return [
            // Fonction pour remplacer des sous-chaînes dans une chaîne
            new TwigFunction(name: 'Replace', callable: [$this, 'replace']),
            new TwigFunction(name: 'replace', callable: [$this, 'replace']),
        ];
    }

    /**
     * Remplace toutes les occurrences d'une sous-chaîne par une autre dans une chaîne donnée.
     * 
     * @param string|string[] $Remplacer La sous-chaîne ou les sous-chaînes à remplacer
     * @param string|string[] $Par La sous-chaîne ou les sous-chaînes de remplacement
     * @param string $Source La chaîne source dans laquelle effectuer les remplacements
     * @return string La chaîne modifiée après remplacement
     */
    public function replace(string|array $Remplacer, string|array $Par, string $Source): string {

        // Remplacer les occurrences de $Remplacer par $Par dans $Source
        return str_replace(search: $Remplacer, replace: $Par, subject: $Source);
    }
}
