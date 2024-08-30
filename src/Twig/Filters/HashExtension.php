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
 * Extension Twig pour calculer les hash MD5 et SHA1.
 * 
 * Cette classe fournit des filtres Twig pour générer des hash MD5 et SHA1 à partir de chaînes
 * de caractères. Les filtres définis permettent de choisir entre les deux algorithmes de hashage
 * et d'obtenir soit une représentation binaire, soit une représentation hexadécimale du hash.
 */
class HashExtension extends AbstractExtension {

    /**
     * Retourne la liste des filtres Twig disponibles dans cette extension.
     * 
     * @return TwigFilter[] Un tableau de filtres Twig
     */
    public function getFilters(): array {

        return [
            // Filtre pour calculer le hash MD5 avec la première lettre en majuscule
            new TwigFilter(name: 'Md5', callable: [$this, 'MD5'], options: ['is_safe' => ['html']]),
            // Filtre pour calculer le hash MD5 avec la première lettre en minuscule
            new TwigFilter(name: 'md5', callable: [$this, 'MD5'], options: ['is_safe' => ['html']]),
            // Filtre pour calculer le hash SHA1 avec la première lettre en majuscule
            new TwigFilter(name: 'Sha1', callable: [$this, 'SHA1'], options: ['is_safe' => ['html']]),
            // Filtre pour calculer le hash SHA1 avec la première lettre en minuscule
            new TwigFilter(name: 'sha1', callable: [$this, 'SHA1'], options: ['is_safe' => ['html']]),
        ];
    }

    /**
     * Calcule le hash MD5 d'une chaîne donnée.
     * 
     * Cette méthode utilise la fonction md5() de PHP pour générer un hash MD5 de la chaîne fournie.
     * 
     * @param string $string La chaîne pour laquelle calculer le hash MD5
     * @param bool|null $binary (Optionnel) Si vrai (true), retourne le hash sous forme binaire. Par défaut, retourne le hash en hexadécimal.
     * 
     * @return string Le hash MD5 résultant
     */
    public function MD5(string $string, ?bool $binary = false): string {

        // Appeler la fonction md5() de PHP pour calculer le hash MD5
        return md5(string: $string, binary: $binary);
    }

    /**
     * Calcule le hash SHA1 d'une chaîne donnée.
     * 
     * Cette méthode utilise la fonction sha1() de PHP pour générer un hash SHA1 de la chaîne fournie.
     * 
     * @param string $string La chaîne pour laquelle calculer le hash SHA1
     * @param bool|null $binary (Optionnel) Si vrai (true), retourne le hash sous forme binaire. Par défaut, retourne le hash en hexadécimal.
     * 
     * @return string Le hash SHA1 résultant
     */
    public function SHA1(string $string, ?bool $binary = false): string {

        // Appeler la fonction sha1() de PHP pour calculer le hash SHA1
        return sha1(string: $string, binary: $binary);
    }
}
