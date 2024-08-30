<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Représente un fichier téléversé dans l'application.
 */
#[ORM\Entity(repositoryClass: FileRepository::class)]
class File {
    /**
     * Identifiant unique du fichier.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Nom du fichier sur le serveur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 245)]
    private ?string $Filename = null;

    /**
     * Nom original du fichier.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 245)]
    private ?string $OriginalName = null;

    /**
     * Chemin d'accès du fichier dans le système de fichiers.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $Path = null;

    /**
     * Extension du fichier (ex : 'jpg', 'pdf').
     * 
     * @var string|null
     */
    #[ORM\Column(length: 10)]
    private ?string $Extension = null;

    /**
     * Taille du fichier en octets.
     * 
     * @var string|null
     */
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $Size = null;

    /**
     * Date et heure de l'upload du fichier.
     * 
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $UploadAt = null;

    /**
     * Post du forum auquel le fichier est associé.
     * 
     * @var ForumPost|null
     */
    #[ORM\ManyToOne(inversedBy: 'Files')]
    private ?ForumPost $Post = null;

    public function __construct() {

        $this->UploadAt = new \DateTimeImmutable;
    }

    /**
     * Retourne l'identifiant unique du fichier.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le nom du fichier sur le serveur.
     * 
     * @return string|null
     */
    public function getFilename(): ?string {

        return $this->Filename;
    }

    /**
     * Définit le nom du fichier sur le serveur.
     * 
     * @param string $Filename
     * @return static
     */
    public function setFilename(string $Filename): static {

        $this->Filename = $Filename;

        return $this;
    }

    /**
     * Retourne le nom original du fichier.
     * 
     * @return string|null
     */
    public function getOriginalName(): ?string {

        return $this->OriginalName;
    }

    /**
     * Définit le nom original du fichier.
     * 
     * @param string $OriginalName
     * @return static
     */
    public function setOriginalName(string $OriginalName): static {

        $this->OriginalName = $OriginalName;

        return $this;
    }

    /**
     * Retourne le chemin d'accès du fichier dans le système de fichiers.
     * 
     * @return string|null
     */
    public function getPath(): ?string {

        return $this->Path;
    }

    /**
     * Définit le chemin d'accès du fichier dans le système de fichiers.
     * 
     * @param string $Path
     * @return static
     */
    public function setPath(string $Path): static {

        $this->Path = $Path;

        return $this;
    }

    /**
     * Retourne l'extension du fichier.
     * 
     * @return string|null
     */
    public function getExtension(): ?string {

        return $this->Extension;
    }

    /**
     * Définit l'extension du fichier.
     * 
     * @param string $Extension
     * @return static
     */
    public function setExtension(string $Extension): static {

        $this->Extension = $Extension;

        return $this;
    }

    /**
     * Retourne la taille du fichier en octets.
     * 
     * @return string|null
     */
    public function getSize(): ?string {

        return $this->Size;
    }

    /**
     * Définit la taille du fichier en octets.
     * 
     * @param string $Size
     * @return static
     */
    public function setSize(string $Size): static {

        $this->Size = $Size;

        return $this;
    }

    /**
     * Retourne la date et l'heure de l'upload du fichier.
     * 
     * @return \DateTimeImmutable|null
     */
    public function getUploadAt(): ?\DateTimeImmutable {

        return $this->UploadAt;
    }

    /**
     * Définit la date et l'heure de l'upload du fichier.
     * 
     * @param \DateTimeImmutable $UploadAt
     * @return static
     */
    public function setUploadAt(\DateTimeImmutable $UploadAt): static {

        $this->UploadAt = $UploadAt;

        return $this;
    }

    /**
     * Retourne le post du forum auquel le fichier est associé.
     * 
     * @return ForumPost|null
     */
    public function getPost(): ?ForumPost {
        
        return $this->Post;
    }

    /**
     * Définit le post du forum auquel le fichier est associé.
     * 
     * @param ForumPost|null $Post
     * @return static
     */
    public function setPost(?ForumPost $Post): static {

        $this->Post = $Post;

        return $this;
    }
}
