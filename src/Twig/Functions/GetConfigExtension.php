<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Twig\Functions;

use App\Service\ConfigService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Extension Twig pour obtenir des valeurs de configuration à partir d'un service.
 * 
 * Cette classe fournit des fonctions Twig pour récupérer les valeurs de configuration
 * en utilisant un service de configuration. Les fonctions définies ici permettent aux
 * templates Twig d'accéder aux valeurs de configuration stockées dans le service.
 */
class GetConfigExtension extends AbstractExtension {
    
    /**
     * Service de configuration utilisé pour obtenir les valeurs de configuration.
     * 
     * @var ConfigService
     */
    private ConfigService $configService;

    /**
     * Constructeur pour injecter le service de configuration.
     * 
     * @param ConfigService $configService Le service de configuration
     */
    public function __construct(ConfigService $configService) {

        $this->configService = $configService;
    }

    /**
     * Retourne la liste des fonctions Twig disponibles dans cette extension.
     * 
     * @return TwigFunction[] Un tableau de fonctions Twig
     */
    public function getFunctions(): array {

        return [
            // Fonction pour obtenir une valeur de configuration par son nom
            new TwigFunction(name: 'GetConfig', callable: [$this, 'getConfigValue']),
            new TwigFunction(name: 'getConfig', callable: [$this, 'getConfigValue']),
        ];
    }

    /**
     * Récupère la valeur d'une configuration par son nom.
     * 
     * @param string $Name Le nom de la configuration dont on souhaite obtenir la valeur
     * @param string|null $Type Le type de configuration
     * @return string|null La valeur de la configuration, ou null si elle n'existe pas
     */
    public function getConfigValue(string $Name, ?string $Type = null): ?string {
        
        if (is_null(value: $Type) || empty($Type)) {
            
            $Type = ucfirst(string: $Type);
        }

        $Name = ucfirst(string: $Name);

        if ($Type === null || $Type === "" || $Type === 'Site' || $Type === 'Web') {

            $Prefix = "Site_";
        } else if ($Type === "Forum") {

            $Prefix = "Forum_";
        }


        return $this->configService->getConfigValue(Name: $Prefix . $Name);
    }
}
