<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Security\AntiSpam\Puzzle;

use App\Security\Interface\AntiSpam\ChallengeInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Implémentation d'un défi de puzzle anti-spam.
 * 
 * Cette classe fournit une méthode pour générer, vérifier et obtenir des réponses pour
 * un défi de puzzle anti-spam. Les puzzles sont stockés dans la session de l'utilisateur.
 */
class PuzzleChallenge implements ChallengeInterface {

    /**
     * Largeur du puzzle en pixels.
     */
    public const WIDTH = 350;

    /**
     * Hauteur du puzzle en pixels.
     */
    public const HEIGHT = 200;

    /**
     * Largeur d'une pièce du puzzle en pixels.
     */
    public const PIECE_WIDTH = 60;

    /**
     * Hauteur d'une pièce du puzzle en pixels.
     */
    public const PIECE_HEIGHT = 60;

    /**
     * Clé de session pour stocker les puzzles.
     */
    private const SESSION_KEY = "Puzzles";

    /**
     * Précision pour la vérification de la position.
     */
    private const PRECISION = 1;

    private readonly RequestStack $Stack;

    /**
     * Constructeur.
     * 
     * @param RequestStack $stack Le gestionnaire des requêtes HTTP.
     */
    public function __construct(RequestStack $stack) {

        $this->Stack = $stack;
    }

    /**
     * Génère une clé pour un puzzle et stocke les informations du puzzle dans la session.
     * 
     * @return string La clé du puzzle généré.
     */
    public function generateKey(): string {

        $session = $this->getSession();
        $now = time();

        // Génère une position aléatoire pour la pièce du puzzle
        $x = mt_rand(
            min: 0, 
            max: self::WIDTH - self::PIECE_WIDTH
        );
        $y = mt_rand(
            min: 0, 
            max: self::HEIGHT - self::PIECE_HEIGHT
        );

        // Récupère les puzzles existants de la session et ajoute le nouveau puzzle
        $puzzles = $session->get(
            name: self::SESSION_KEY, 
            default: []
        );
        $puzzles[] = [
            "Key" => $now,
            "Solution" => [$x, $y]
        ];

        // Ne garde que les 10 puzzles les plus récents
        $session->set(
            name: self::SESSION_KEY, 
            value: array_slice(
                array: $puzzles, 
                offset: -10
            )
        );

        return (string)$now;
    }

    /**
     * Vérifie si la réponse fournie pour une clé donnée est correcte.
     * 
     * @param string $key La clé du puzzle à vérifier.
     * @param string $answer La réponse fournie par l'utilisateur.
     * 
     * @return bool True si la réponse est correcte, sinon false.
     */
    public function verify(string $Key, string $Answer): bool {

        $expected = $this->getAnswer($Key);

        // Si aucune réponse n'est trouvée pour la clé donnée, retourne false
        if (!$expected) {

            return false;
        }

        $session = $this->getSession();
        $puzzles = $session->get(name: self::SESSION_KEY);

        // Filtre les puzzles pour ne conserver que ceux avec la clé donnée
        $session->set(
            name: self::SESSION_KEY, 
            value: array_filter(
                array: $puzzles, 
                callback: fn(array $puzzle) => $puzzle["Key"] === intval($Key)
            )
        );

        // Convertit la réponse fournie en position
        $got = $this->stringToPosition(String: $Answer);

        // Vérifie si la position donnée est suffisamment proche de la position attendue
        return abs(num: $expected[0] - $got[0]) < self::PRECISION 
            && abs(num: $expected[1] - $got[1]) < self::PRECISION;
    }

    /**
     * Obtient la réponse pour une clé donnée.
     * 
     * @param string $key La clé du puzzle pour laquelle obtenir la réponse.
     * 
     * @return array|null La solution du puzzle ou null si la clé n'existe pas.
     */
    public function getAnswer(string $Key): array | null {

        $puzzles = $this->getSession()->get(name: self::SESSION_KEY, default: []);

        // Parcourt les puzzles pour trouver la solution correspondant à la clé donnée
        foreach ($puzzles as $puzzle) {

            if ($puzzle["Key"] === intval(value: $Key)) {

                return $puzzle["Solution"];
            }
        }

        return null;
    }

    /**
     * Convertit une chaîne représentant une position en tableau de coordonnées.
     * 
     * @param string $string La chaîne représentant la position, au format "x-y".
     * 
     * @return array Tableau de coordonnées [x, y] ou [-1, -1] si le format est incorrect.
     */
    public function stringToPosition(string $String): array {

        // Sépare la chaîne par le caractère "-"
        $position = explode(separator: "-", string: $String, limit: 2);

        // Si le format est incorrect, retourne une position invalide
        if (count(value: $position) !== 2) {

            return [-1, -1];
        }

        // Convertit les parties de la chaîne en entiers
        return [intval(value: $position[0]), intval(value: $position[1])];
    }

    /**
     * Obtient la session courante.
     * 
     * @return SessionInterface La session courante.
     */
    private function getSession(): SessionInterface {

        return $this->Stack->getMainRequest()->getSession();
    }
}
