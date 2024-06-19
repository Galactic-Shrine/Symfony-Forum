<?php

namespace App\Config;

abstract class AutoSlug {

    // Variable privée pour l'encodage
    private static $Encoding = 'utf-8';

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

    // Méthode publique pour définir un nouvel encodage
    public static function setEncoding(string $encoding) : void {
        self::$Encoding = $encoding;
    }

    // Méthode protégée pour obtenir le tableau de translitération
    protected static function getTransliteration() : array {
        return self::$Transliteration;
    }

    // Méthode privée pour normaliser la chaîne
    private static function normalize(string $String, bool $ToLowercase = true, bool $ToUppercase = false) : string {
        if ($ToLowercase) {
            $String = mb_strtolower($String, self::$Encoding);
        } elseif ($ToUppercase) {
            $String = mb_strtoupper($String, self::$Encoding);
        }

        mb_regex_encoding(self::$Encoding);

        $string = trim(preg_replace('/ +/', ' ', mb_ereg_replace('[^a-zA-Z\p{L}]+', ' ', $String)));
        return strtr($string, self::getTransliteration());
    }

    // Méthode publique pour convertir la chaîne en slug en minuscules
    public static function toLowercaseSlug(string $string) : string {
        return self::normalize($string);
    }

    // Méthode publique pour convertir la chaîne en slug avec majuscules
    public static function toSlugPreserveCase(string $string) : string {
        return self::normalize($string, false);
    }

    // Méthode publique pour convertir la chaîne en slug en majuscules
    public static function toUppercaseSlug(string $string) : string {
        return self::normalize($string, false, true);
    }

    // Méthode publique pour ajouter ou modifier une entrée dans le tableau de translitération
    public static function addTransliteration(string $character, string $replacement) : void {
        self::$Transliteration[$character] = $replacement;
    }
}
