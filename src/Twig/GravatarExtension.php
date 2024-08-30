<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Extension Twig pour générer des URL d'avatars Gravatar.
 * 
 * Cette extension fournit des filtres et des fonctions Twig pour générer des URLs
 * d'avatars Gravatar basées sur l'adresse e-mail de l'utilisateur. Les tailles et
 * les extensions d'avatar peuvent également être spécifiées.
 */
class GravatarExtension extends AbstractExtension {

    /**
     * Retourne la liste des filtres Twig fournis par cette extension.
     * 
     * Les filtres permettent de transformer les valeurs dans les templates Twig.
     * 
     * @return TwigFilter[] Un tableau de filtres Twig
     */
    public function getFilters(): array {
        return [
            // Filtre pour générer une URL d'avatar Gravatar, avec la première lettre en majuscule
            new TwigFilter(name: 'Gravatar', callable: [$this, 'gravatar'], options: ['is_safe' => ['html']]),
            // Filtre pour générer une URL d'avatar Gravatar, avec la première lettre en minuscule
            new TwigFilter(name: 'gravatar', callable: [$this, 'gravatar'], options: ['is_safe' => ['html']]),
        ];
    }

    /**
     * Retourne la liste des fonctions Twig fournies par cette extension.
     * 
     * Les fonctions permettent de créer des appels de méthode dans les templates Twig.
     * 
     * @return TwigFunction[] Un tableau de fonctions Twig
     */
    public function getFunctions(): array {
        return [
            // Fonction pour générer une URL d'avatar Gravatar, avec la première lettre en majuscule
            new TwigFunction(name: 'Gravatar', callable: [$this, 'gravatar']),
            // Fonction pour générer une URL d'avatar Gravatar, avec la première lettre en minuscule
            new TwigFunction(name: 'gravatar', callable: [$this, 'gravatar']),
        ];
    }

    /**
     * Génère l'URL d'un avatar Gravatar basé sur l'adresse e-mail fournie.
     * 
     * Cette méthode crée l'URL de l'avatar Gravatar en fonction de l'adresse e-mail,
     * de la taille spécifiée et de l'extension de fichier. L'adresse e-mail est convertie
     * en minuscules et son hash MD5 est utilisé pour générer l'URL de l'avatar.
     * 
     * @param string $Mail L'adresse e-mail pour générer l'avatar Gravatar
     * @param int|null $Size La taille de l'avatar en pixels (optionnelle)
     * @param string|null $Extention L'extension du fichier de l'avatar (optionnelle)
     * @return string L'URL de l'avatar Gravatar généré
     */
    public function gravatar(string $Mail, ?int $Size = 180, ?string $Extention = null): string {
        // Convertir l'adresse e-mail en minuscules et supprimer les espaces inutiles
        $Mail = strtolower(string: trim(string: $Mail));
        // Obtenir le hash MD5 de l'adresse e-mail
        $MailMd5 = md5(string: $Mail);
        // Définir l'extension de l'avatar, s'il est fourni
        $Extention = ($Extention === null) ? '' : '.' . $Extention;
        // Définir la taille de l'avatar, s'il est fourni
        $Size = ($Size === null) ? '' : 's=' . $Size;

        // Retourner l'URL de l'avatar Gravatar généré
        return "https://s.gravatar.com/avatar/{$MailMd5}{$Extention}?{$Size}";
    }
}
