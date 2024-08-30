<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Service;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Service pour gérer les paramètres de configuration.
 * 
 * Cette classe fournit des méthodes pour accéder et manipuler les paramètres de
 * configuration stockés dans la base de données. Elle utilise le gestionnaire d'entités
 * Doctrine pour interagir avec les entités de configuration.
 */
class ConfigService {

    /**
     * Le gestionnaire d'entités Doctrine pour interagir avec la base de données
     * 
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $em;
    private CacheInterface $cache;

    /**
     * Constructeur pour injecter le service EntityManagerInterface et le cache.
     * 
     * @param EntityManagerInterface $em Le gestionnaire d'entités Doctrine
     * @param CacheInterface $cache Le service de cache
     */
    public function __construct(EntityManagerInterface $em, CacheInterface $cache) {
        
        $this->em = $em;
        $this->cache = $cache;
    }

    /**
     * Récupère la valeur d'une configuration par son nom.
     * 
     * @param string $Name Le nom de la configuration dont on souhaite obtenir la valeur
     * @return string|null La valeur de la configuration, ou null si elle n'existe pas
     */
    public function getConfigValue(string $Name): ?string {

        return $this->cache->get(key: 'config_' . $Name, callback: function (ItemInterface $item) use ($Name) {

            // Définir le TTL (Time to Live) à 10 minutes
            $item->expiresAfter(600);

            // Récupérer l'entité Config correspondant au nom spécifié
            $Config = $this->em->getRepository(className: Config::class)->findOneBy(
                criteria: ['Name' => $Name]
            );

            // Retourner la valeur de la configuration si elle existe, sinon null
            return $Config ? $Config->getValue() : null;
        });
    }
}
