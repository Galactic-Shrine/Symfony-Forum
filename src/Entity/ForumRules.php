<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Repository\ForumRulesRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Représente les règles du forum pour une langue spécifique.
 */
#[ORM\Entity(repositoryClass: ForumRulesRepository::class)]
class ForumRules {

    /**
     * Identifiant unique des règles du forum.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Langue dans laquelle les règles sont rédigées.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 50)]
    private ?string $Lang = null;

    /**
     * Contenu des règles du forum.
     * 
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $Content = null;

    /**
     * Retourne l'identifiant unique des règles du forum.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne la langue dans laquelle les règles sont rédigées.
     * 
     * @return string|null
     */
    public function getLang(): ?string {

        return $this->Lang;
    }

    /**
     * Définit la langue dans laquelle les règles sont rédigées.
     * 
     * @param string $Lang La langue des règles.
     * @return static
     */
    public function setLang(string $Lang): static {

        $this->Lang = $Lang;

        return $this;
    }

    /**
     * Retourne le contenu des règles du forum.
     * 
     * @return string|null
     */
    public function getContent(): ?string {

        return $this->Content;
    }

    /**
     * Définit le contenu des règles du forum.
     * 
     * @param string $Content Le contenu des règles.
     * @return static
     */
    public function setContent(string $Content): static {
        
        $this->Content = $Content;

        return $this;
    }
}
