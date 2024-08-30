<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Représente une configuration de l'application.
 */
#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config {

    /**
     * Identifiant unique de la configuration.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Nom de la configuration.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    /**
     * Valeur associée à la configuration.
     * 
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Value = null;

    /**
     * Retourne l'identifiant unique de la configuration.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le nom de la configuration.
     * 
     * @return string|null
     */
    public function getName(): ?string {

        return $this->Name;
    }

    /**
     * Définit le nom de la configuration.
     * 
     * @param string $Name
     * @return static
     */
    public function setName(string $Name): static {

        $this->Name = $Name;

        return $this;
    }

    /**
     * Retourne la valeur associée à la configuration.
     * 
     * @return string|null
     */
    public function getValue(): ?string {

        return $this->Value;
    }

    /**
     * Définit la valeur associée à la configuration.
     * 
     * @param string|null $Value
     * @return static
     */
    public function setValue(?string $Value): static {

        $this->Value = $Value;

        return $this;
    }
}
