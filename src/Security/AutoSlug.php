<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author Galactic-Shrine <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Security;

use InvalidArgumentException;
use RuntimeException;

/**
 * Classe abstraite pour la génération de slugs à partir de chaînes de caractères.
 * 
 * Cette classe fournit des méthodes pour créer des slugs (identifiants URL) à partir de chaînes
 * de caractères, en normalisant et en translittérant les caractères. Les slugs peuvent être
 * générés en minuscules, en majuscules ou en préservant la casse d'origine.
 */
abstract class AutoSlug {
    /**
     * Encodage des chaînes de caractères utilisé pour la normalisation.
     * 
     * @var string
     */
    private static $Encoding = 'utf-8';

    /**
     * Modèle d'expression régulière utilisé pour remplacer les caractères non valides.
     * 
     * @var string
     */
    private static $RegexPattern = '[^a-zA-Z\p{L}]+';

    /**
     * Tableau de translittération des caractères spéciaux en caractères ASCII.
     * 
     * @var array<string, string>
     */
    protected static $Transliteration = [
        ' ' => '-', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
        'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i',
        'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ñ' => 'n', 'ð' => 'o', 'ò' => 'o', 'ó' => 'o',
        'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'œ' => 'o', 'ø' => 'o', 'š' => 's', 'ù' => 'u',
        'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'ÿ' => 'y', 'ž' => 'z', 'À' => 'A',
        'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I',
        'Ï' => 'I', 'Ñ' => 'N', 'Ð' => 'O', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
        'Ö' => 'O', 'Œ' => 'O', 'Ø' => 'O', 'Š' => 'S', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U',
        'Ü' => 'U', 'Ý' => 'Y', 'Ÿ' => 'Y', 'Ž' => 'Z'
    ];

    /**
     * Obtient le tableau de translittération utilisé pour convertir les caractères spéciaux.
     * 
     * @return array<string, string> Le tableau de translittération.
     */
    protected static function getTransliteration() : array {

        return self::$Transliteration;
    }

    /**
     * Définit l'encodage utilisé pour la normalisation des chaînes de caractères.
     * 
     * @param string $Encoding L'encodage à utiliser.
     * 
     * @return void
     */
    public static function setEncoding(string $Encoding) : void {

        self::$Encoding = (string) $Encoding;
    }

    /**
     * Définit le modèle d'expression régulière utilisé pour remplacer les caractères non valides.
     * 
     * @param string $Pattern Le modèle d'expression régulière.
     * 
     * @return void
     */
    public static function setRegexPattern(string $Pattern) : void {

        self::$RegexPattern = (string) $Pattern;
    }

    /**
     * Ajoute une règle de translittération pour un caractère donné.
     * 
     * @param string $Character Le caractère à translittérer.
     * @param string $Replacement La chaîne de remplacement pour le caractère.
     * 
     * @return void
     */
    public static function addTransliteration(string $Character, string $Replacement) : void {

        self::$Transliteration[$Character] = $Replacement;
    }

    /**
     * Valide que la chaîne d'entrée n'est pas vide.
     * 
     * @param string $string La chaîne à valider.
     * 
     * @throws InvalidArgumentException Si la chaîne est vide.
     * 
     * @return void
     */
    private static function validateInput(string $String) : void {

        if (empty($String)) {

            throw new InvalidArgumentException('La chaîne d\'entrée ne peut pas être vide.');
        }
    }

    /**
     * Normalise une chaîne de caractères en fonction des paramètres spécifiés.
     * 
     * Cette méthode applique la translittération, les transformations de casse et les nettoyages
     * nécessaires à la chaîne.
     * 
     * @param string $String La chaîne à normaliser.
     * @param bool $ToLowercase Si vrai, convertit la chaîne en minuscules.
     * @param bool $ToUppercase Si vrai, convertit la chaîne en majuscules.
     * 
     * @return string La chaîne normalisée.
     * 
     * @throws RuntimeException Si une erreur survient lors de la normalisation.
     */
    private static function normalize(string $String, bool $ToLowercase = false, bool $ToUppercase = false) : string {

        try {

            if ($ToLowercase) {

                $String = mb_strtolower(string: $String, encoding: self::$Encoding);
            } elseif ($ToUppercase) {

                $String = mb_strtoupper(string: $String, encoding: self::$Encoding);
            }

            mb_regex_encoding(self::$Encoding);

            // Nettoyer la chaîne en remplaçant les caractères non valides et les espaces multiples
            $string = trim(
                string: preg_replace(
                    pattern: '/ +/', 
                    replacement: ' ', 
                    subject: mb_ereg_replace(
                        pattern: self::$RegexPattern, 
                        replacement: ' ', 
                        string: $String
                    )
                )
            );

            return strtr(string: $string, replace_pairs: self::getTransliteration());
        } catch (\Exception $e) {

            throw new RuntimeException(message: 'Erreur lors de la normalisation de la chaîne : ' . $e->getMessage());
        }
    }

    /**
     * Crée un slug en minuscules à partir de la chaîne spécifiée.
     * 
     * @param string $string La chaîne à convertir en slug.
     * 
     * @return string Le slug en minuscules.
     */
    public static function toLowercaseSlug(string $String) : string {

        self::validateInput(String: $String);
        return self::normalize(String: $String, ToLowercase: true);
    }

    /**
     * Crée un slug à partir de la chaîne spécifiée en préservant la casse.
     * 
     * @param string $string La chaîne à convertir en slug.
     * 
     * @return string Le slug en préservant la casse.
     */
    public static function toSlugPreserveCase(string $String) : string {

        self::validateInput(String: $String);
        return self::normalize(String: $String);
    }

    /**
     * Crée un slug en majuscules à partir de la chaîne spécifiée.
     * 
     * @param string $string La chaîne à convertir en slug.
     * 
     * @return string Le slug en majuscules.
     */
    public static function toUppercaseSlug(string $String) : string {

        self::validateInput(String: $String);
        return self::normalize(String: $String, ToUppercase: true);
    }
}
