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
 * Extension Twig pour gérer la pluralisation des chaînes de caractères.
 * 
 * Cette classe fournit des filtres Twig pour pluraliser des chaînes en fonction d'un compteur.
 * Les filtres définis ici permettent de choisir entre une forme singulière et une forme plurielle
 * en fonction de la valeur du compteur, avec des options supplémentaires pour gérer les cas spéciaux.
 */
class PluraliserExtension extends AbstractExtension {

    /**
     * Retourne la liste des filtres Twig disponibles dans cette extension.
     * 
     * @return TwigFilter[] Un tableau de filtres Twig
     */
    public function getFilters(): array {

        return [
            // Filtre pour pluraliser les chaînes avec la première lettre en majuscule
            new TwigFilter(name: 'Pluralize', callable: [$this, 'pluralize']),
            // Filtre pour pluraliser les chaînes avec la première lettre en minuscule
            new TwigFilter(name: 'pluralize', callable: [$this, 'pluralize']),
        ];
    }
    
    /**
     * Gère la pluralisation des chaînes en fonction d'un compteur.
     * 
     * Cette fonction retourne une chaîne soit au singulier, soit au pluriel, selon la valeur du compteur.
     * Elle gère également les cas où le compteur est inférieur ou égal à zéro, avec des options
     * supplémentaires pour inclure une valeur ajoutée ou retourner une chaîne spéciale.
     * 
     * @param int $Count Le compteur utilisé pour déterminer la forme de la chaîne
     * @param string $Singular La chaîne au singulier
     * @param string $Plural La chaîne au pluriel
     * @param bool $Addedvalue (Optionnel) Un booléen indiquant si une valeur ajoutée doit être incluse dans la chaîne singulière absolue
     * @param string|null $SingularPriorityApsolu (Optionnel) La chaîne à retourner si le compteur est égal ou inférieur à zéro
     * 
     * @return string La chaîne résultante en fonction du compteur fourni
     */
    public function pluralize(int $Count, string $Singular, string $Plural, bool $Addedvalue = false, string $SingularPriorityApsolu = null): string {
        
        // Si le compteur est supérieur à 1, retourner la chaîne plurielle avec la valeur du compteur
        if ($Count > 1) {

            return str_replace(search: '%Valeur%', replace: $Count, subject: $Plural);
        }
        // Si le compteur est inférieur ou égal à zéro et qu'une chaîne singulière absolue est fournie, la retourner
        else if ($Count <= 0 && null !== $SingularPriorityApsolu) {

            return $SingularPriorityApsolu; // Aucun remplacement de chaîne n'est nécessaire pour le Singulier Absolu
        } 
        // Si le compteur est inférieur ou égal à zéro, qu'une chaîne singulière absolue est fournie et que la valeur ajoutée est activée,
        // retourner la chaîne singulière absolue avec la valeur du compteur
        else if ($Count <= 0 && null !== $SingularPriorityApsolu && false !== $Addedvalue) {
			
            return str_replace(search: '%Valeur%', replace: $Count, subject: $SingularPriorityApsolu);
        } 

        // Sinon, retourner la chaîne singulière avec la valeur du compteur
        return str_replace(search: '%Valeur%', replace: $Count, subject: $Singular);
    }
}
