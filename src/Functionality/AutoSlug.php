<?php 

namespace App\Functionality;

use InvalidArgumentException;
use RuntimeException;

/**
 * Classe abstraite pour la génération de slugs à partir de chaînes de caractères.
 *
 * Cette classe fournit des méthodes statiques pour convertir des chaînes en slugs utilisant diverses options de normalisation.
 */
abstract class AutoSlug {

    // Variable privée pour l'encodage
    private static $Encoding = 'utf-8';

    // Variable privée pour le modèle de regex
    private static $RegexPattern = '[^a-zA-Z\p{L}]+';

    // Tableau de remplacement pour les caractères spéciaux
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
     * Obtient le tableau de translittération utilisé pour convertir les caractères spéciaux en leur équivalent normalisé.
     *
     * @return array Le tableau de translittération.
     */
    protected static function getTransliteration() : array {

        return self::$Transliteration;
    }

    /**
     * Définit l'encodage à utiliser pour les opérations de normalisation de chaîne.
     *
     * @param string $Encoding Le nouvel encodage à utiliser.
     */
    public static function setEncoding(string $Encoding) : void {

        self::$Encoding = (string) $Encoding;
    }

    /**
     * Définit le modèle de pattern regex à utiliser pour les opérations de normalisation de chaîne.
     *
     * @param string $Pattern Le nouveau modèle de pattern regex à utiliser.
     */
    public static function setRegexPattern(string $Pattern) : void {

        self::$RegexPattern = (string) $Pattern;
    }

    /**
     * Ajoute ou modifie une entrée dans le tableau de translittération des caractères spéciaux.
     *
     * @param string $Character Le caractère spécial à remplacer.
     * @param string $Replacement Le caractère de remplacement.
     */
    public static function addTransliteration(string $Character, string $Replacement) : void {

        self::$Transliteration[$Character] = $Replacement;
    }

    /**
     * Valide la chaîne d'entrée pour s'assurer qu'elle n'est pas vide.
     *
     * @param string $string La chaîne à valider.
     * @throws InvalidArgumentException Si la chaîne d'entrée est vide.
     */
    private static function validateInput(string $string) : void {

        if (empty($string)) {

            throw new InvalidArgumentException('La chaîne d\'entrée ne peut pas être vide.');
        }
    }

    /**
     * Normalise une chaîne en un slug en fonction des options spécifiées.
     *
     * @param string $String La chaîne à normaliser.
     * @param bool $ToLowercase Convertir en minuscules.
     * @param bool $ToUppercase Convertir en majuscules.
     * @return string La chaîne normalisée en slug.
     * @throws RuntimeException En cas d'erreur lors de la normalisation de la chaîne.
     */
    private static function normalize(string $String, bool $ToLowercase = false, bool $ToUppercase = false) : string {

        try {

            if ($ToLowercase) {

                $String = mb_strtolower($String, self::$Encoding);
            } 
            elseif ($ToUppercase) {

                $String = mb_strtoupper($String, self::$Encoding);
            }

            mb_regex_encoding(self::$Encoding);

            $string = trim(preg_replace('/ +/', ' ', mb_ereg_replace(self::$RegexPattern, ' ', $String)));
            return strtr(string: $string, replace_pairs: self::getTransliteration());
        } 
        catch (\Exception $e) {

            throw new RuntimeException('Erreur lors de la normalisation de la chaîne : ' . $e->getMessage());
        }
    }

    /**
     * Convertit la chaîne en slug en minuscules.
     *
     * @param string $string La chaîne à convertir en slug.
     * @return string Le slug généré en minuscules.
     * @throws InvalidArgumentException Si la chaîne d'entrée est vide.
     */
    public static function toLowercaseSlug(string $string) : string {

        self::validateInput($string);
        return self::normalize(String: $string, ToLowercase: true);
    }

    /**
     * Convertit la chaîne en slug en conservant la casse d'origine.
     *
     * @param string $string La chaîne à convertir en slug.
     * @return string Le slug généré en conservant la casse d'origine.
     * @throws InvalidArgumentException Si la chaîne d'entrée est vide.
     */
    public static function toSlugPreserveCase(string $string) : string {

        self::validateInput($string);
        return self::normalize(String: $string);
    }

    /**
     * Convertit la chaîne en slug en majuscules.
     *
     * @param string $string La chaîne à convertir en slug.
     * @return string Le slug généré en majuscules.
     * @throws InvalidArgumentException Si la chaîne d'entrée est vide.
     */
    public static function toUppercaseSlug(string $string) : string {
        
        self::validateInput($string);
        return self::normalize(String: $string, ToUppercase: true);
    }
}
