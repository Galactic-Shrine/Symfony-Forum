<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Security\Interface\AntiSpam;

/**
 * Interface pour un mécanisme anti-spam challenge.
 * 
 * Cette interface définit les méthodes nécessaires pour générer, vérifier et récupérer
 * les réponses à des défis anti-spam, tels que les CAPTCHAs ou autres mécanismes de validation.
 */
interface ChallengeInterface {

    /**
     * Génère une clé unique pour un nouveau défi anti-spam.
     * 
     * @return string La clé générée pour le défi.
     */
    public function generateKey(): string;

    /**
     * Vérifie si la réponse fournie correspond à la clé spécifiée.
     * 
     * @param string $key La clé du défi pour laquelle vérifier la réponse.
     * @param string $answer La réponse fournie par l'utilisateur.
     * 
     * @return bool Retourne vrai si la réponse est correcte, sinon faux.
     */
    public function verify(string $Key, string $Answer): bool;

    /**
     * Récupère la réponse correcte pour une clé donnée.
     * 
     * @param string $key La clé du défi pour laquelle obtenir la réponse correcte.
     * 
     * @return mixed La réponse correcte associée à la clé.
     */
    public function getAnswer(string $Key): mixed;
}
