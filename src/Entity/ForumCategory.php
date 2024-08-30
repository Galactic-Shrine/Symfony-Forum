<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Entity\ForumForum;
use App\Repository\ForumCategoryRepository;
use Doctrine\ORM\Mapping as ORM; 
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Représente une catégorie de forum dans l'application.
 */
#[ORM\Entity(repositoryClass: ForumCategoryRepository::class)]
class ForumCategory {

    /**
     * Identifiant unique de la catégorie.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Nom de la catégorie.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    /**
     * Description de la catégorie.
     * 
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Description = null;

    /**
     * Liste des forums associés à cette catégorie.
     * 
     * @var Collection<Uuid, ForumForum>
     */
    #[ORM\OneToMany(targetEntity: ForumForum::class, mappedBy: 'Category')]
    private Collection $Forum;

    /**
     * Slug de la catégorie (pour l'URL).
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    /**
     * Position de la catégorie dans l'ordre d'affichage.
     * 
     * @var int|null
     */
    #[ORM\Column(type: "integer", options: ["unsigned" => true, "default" => 0])]
    private ?int $Position = null;

    /**
     * Rôles autorisés pour accéder à cette catégorie.
     * 
     * @var array|null
     */
    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $AuthorizedRoles = null;

    /**
     * Constructeur de la classe, initialise la collection de forums.
     */
    public function __construct() {

        $this->Forum = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique de la catégorie.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le nom de la catégorie.
     * 
     * @return string|null
     */
    public function getName(): ?string {

        return $this->Name;
    }

    /**
     * Définit le nom de la catégorie.
     * 
     * @param string $Name
     * @return static
     */
    public function setName(string $Name): static {

        $this->Name = $Name;

        return $this;
    }

    /**
     * Retourne la description de la catégorie.
     * 
     * @return string|null
     */
    public function getDescription(): ?string {

        return $this->Description;
    }

    /**
     * Définit la description de la catégorie.
     * 
     * @param string|null $Description
     * @return static
     */
    public function setDescription(?string $Description): static {

        $this->Description = $Description;

        return $this;
    }

    /**
     * Retourne la collection des forums associés à cette catégorie.
     * 
     * @return Collection<Uuid, ForumForum>
     */
    public function getForum(): Collection {

        return $this->Forum;
    }

    /**
     * Ajoute un forum à la catégorie.
     * 
     * @param ForumForum $forum
     * @return static
     */
    public function addForum(ForumForum $forum): static {

        if (!$this->Forum->contains($forum)) {

            $this->Forum->add($forum);
            $forum->setCategory($this);
        }

        return $this;
    }

    /**
     * Supprime un forum de la catégorie.
     * 
     * @param ForumForum $forum
     * @return static
     */
    public function removeForum(ForumForum $forum): static {

        if ($this->Forum->removeElement($forum)) {

            // Définit la catégorie du forum à null si elle correspond à cette catégorie
            if ($forum->getCategory() === $this) {

                $forum->setCategory(null);
            }
        }
        
        return $this;
    }

    /**
     * Retourne le slug de la catégorie.
     * 
     * @return string|null
     */
    public function getSlug(): ?string {

        return $this->Slug;
    }

    /**
     * Définit le slug de la catégorie.
     * 
     * @param string $Slug
     * @return static
     */
    public function setSlug(string $Slug): static {

        $this->Slug = $Slug;

        return $this;
    }

    /**
     * Retourne la position de la catégorie dans l'ordre d'affichage.
     * 
     * @return int|null
     */
    public function getPosition(): ?int {

        return $this->Position;
    }

    /**
     * Définit la position de la catégorie dans l'ordre d'affichage.
     * 
     * @param int $Position
     * @return static
     */
    public function setPosition(int $Position): static {

        $this->Position = $Position;

        return $this;
    }

    /**
     * Retourne les rôles autorisés pour accéder à cette catégorie.
     * 
     * @return array|null
     */
    public function getAuthorizedRoles(): ?array {

        return $this->AuthorizedRoles;
    }

    /**
     * Définit les rôles autorisés pour accéder à cette catégorie.
     * 
     * @param array|null $AuthorizedRoles
     * @return static
     */
    public function setAuthorizedRoles(?array $AuthorizedRoles): static {
        
        $this->AuthorizedRoles = $AuthorizedRoles;

        return $this;
    }

    /**
     * Vérifie si la catégorie a des rôles autorisés définis.
     * 
     * @return bool
     */
    public function hasAuthorizedRoles(): bool {

        // Vérifie si les rôles autorisés sont définis et non vides
        if (!is_null($this->AuthorizedRoles) && count($this->AuthorizedRoles) >= 1 && !empty($this->AuthorizedRoles[0])) {

            return true;
        }

        return false;
    }
}
