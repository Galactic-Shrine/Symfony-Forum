<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Security\Interface\AntiSpam;

use Symfony\Component\HttpFoundation\Response;

/**
 * Interface pour un générateur de défi anti-spam.
 * 
 * Cette interface définit la méthode nécessaire pour générer un défi anti-spam en
 * fonction d'une clé spécifiée. Le défi est généralement présenté à l'utilisateur pour
 * validation, comme un CAPTCHA ou autre forme de challenge.
 */
interface ChallengeGeneratorInterface {

    /**
     * Génère un défi anti-spam en fonction de la clé fournie.
     * 
     * @param string $Key La clé pour laquelle générer le défi anti-spam.
     * 
     * @return Response La réponse contenant le défi généré, prêt à être présenté à l'utilisateur.
     */
    public function generate(string $Key): Response;
}
