<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Entity\ForumPost;
use App\Repository\ForumPostReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Représente un rapport concernant un message dans le forum.
 */
#[ORM\Entity(repositoryClass: ForumPostReportRepository::class)]
class ForumPostReport {

    /**
     * Identifiant unique du rapport.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Message qui est signalé dans le rapport.
     * 
     * @var ForumPost|null
     */
    #[ORM\ManyToOne(inversedBy: 'Report')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumPost $Post = null;

    /**
     * Utilisateur qui a signalé le message.
     * 
     * @var User|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    /**
     * Indique si le rapport a été traité.
     * 
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $Processed = null;

    /**
     * Date et heure de la création du rapport.
     * 
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $CreateAt = null;

    /**
     * Retourne l'identifiant unique du rapport.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le message qui est signalé dans le rapport.
     * 
     * @return ForumPost|null
     */
    public function getPost(): ?ForumPost {

        return $this->Post;
    }

    /**
     * Définit le message qui est signalé dans le rapport.
     * 
     * @param ForumPost|null $Post Message signalé.
     * @return static
     */
    public function setPost(?ForumPost $Post): static {

        $this->Post = $Post;

        return $this;
    }

    /**
     * Retourne l'utilisateur qui a signalé le message.
     * 
     * @return User|null
     */
    public function getUser(): ?User {

        return $this->User;
    }

    /**
     * Définit l'utilisateur qui a signalé le message.
     * 
     * @param User|null $User Utilisateur.
     * @return static
     */
    public function setUser(?User $User): static {

        $this->User = $User;

        return $this;
    }

    /**
     * Indique si le rapport a été traité.
     * 
     * @return bool|null
     */
    public function isProcessed(): ?bool {

        return $this->Processed;
    }

    /**
     * Définit si le rapport a été traité.
     * 
     * @param bool $Processed Traitement du rapport.
     * @return static
     */
    public function setProcessed(bool $Processed): static {

        $this->Processed = $Processed;

        return $this;
    }

    /**
     * Retourne la date et l'heure de la création du rapport.
     * 
     * @return \DateTimeImmutable|null
     */
    public function getCreateAt(): ?\DateTimeImmutable {

        return $this->CreateAt;
    }

    /**
     * Définit la date et l'heure de la création du rapport.
     * 
     * @param \DateTimeImmutable $CreateAt Date et heure de création.
     * @return static
     */
    public function setCreateAt(\DateTimeImmutable $CreateAt): static {
        
        $this->CreateAt = $CreateAt;

        return $this;
    }
}
