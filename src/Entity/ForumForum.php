<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Security\AutoSlug;
use App\Entity\ForumThread;
use App\Entity\ForumSubForum;
use App\Repository\ForumForumRepository; 
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Représente un forum dans l'application.
 */
#[ORM\Entity(repositoryClass: ForumForumRepository::class)]
class ForumForum {

    /**
     * Identifiant unique du forum.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Nom du forum.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    /**
     * Description du forum.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    /**
     * Catégorie à laquelle appartient le forum.
     * 
     * @var ForumCategory|null
     */
    #[ORM\ManyToOne(inversedBy: 'Forum')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumCategory $Category = null;

    /**
     * Liste des sous-forums associés à ce forum.
     * 
     * @var Collection<Uuid, ForumSubForum>
     */
    #[ORM\OneToMany(targetEntity: ForumSubForum::class, mappedBy: 'Forum', orphanRemoval: true)]
    private Collection $SubForum;

    /**
     * Slug du forum (pour l'URL).
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    /**
     * Position du forum dans l'ordre d'affichage.
     * 
     * @var int|null
     */
    #[ORM\Column(type: "integer", options: ["unsigned" => true, "default" => 0])]
    private ?int $Position = null;

    /**
     * Liste des fils de discussion associés à ce forum.
     * 
     * @var Collection<Uuid, ForumThread>
     */
    #[ORM\OneToMany(targetEntity: ForumThread::class, mappedBy: 'Forum')]
    private Collection $Thread;

    /**
     * Rôles autorisés pour accéder à ce forum.
     * 
     * @var array|null
     */
    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $AuthorizedRoles = null;

    /**
     * Constructeur de la classe, initialise les collections de sous-forums et de fils de discussion.
     */
    public function __construct() {

        $this->SubForum = new ArrayCollection();
        $this->Thread = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique du forum.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le nom du forum.
     * 
     * @return string|null
     */
    public function getName(): ?string {

        return $this->Name;
    }

    /**
     * Définit le nom du forum et met à jour le slug si nécessaire.
     * 
     * @param string $Name
     * @return static
     */
    public function setName(string $Name): static {

        $this->Name = $Name;

        // Génère un slug à partir du nom si le slug est vide
        if (empty($this->Slug)) {

            $this->Slug = AutoSlug::toSlugPreserveCase(String: $this->Name);
        }

        return $this;
    }

    /**
     * Retourne la description du forum.
     * 
     * @return string|null
     */
    public function getDescription(): ?string {

        return $this->Description;
    }

    /**
     * Définit la description du forum.
     * 
     * @param string|null $Description
     * @return static
     */
    public function setDescription(?string $Description): static {

        $this->Description = $Description;

        return $this;
    }

    /**
     * Retourne la catégorie à laquelle appartient le forum.
     * 
     * @return ForumCategory|null
     */
    public function getCategory(): ?ForumCategory {

        return $this->Category;
    }

    /**
     * Définit la catégorie à laquelle appartient le forum.
     * 
     * @param ForumCategory|null $Category
     * @return static
     */
    public function setCategory(?ForumCategory $Category): static {

        $this->Category = $Category;

        return $this;
    }

    /**
     * Retourne la collection des sous-forums associés à ce forum.
     * 
     * @return Collection<Uuid, ForumSubForum>
     */
    public function getSubForum(): Collection {

        return $this->SubForum;
    }

    /**
     * Ajoute un sous-forum au forum.
     * 
     * @param ForumSubForum $subForum
     * @return static
     */
    public function addSubForum(ForumSubForum $subForum): static {

        if (!$this->SubForum->contains($subForum)) {

            $this->SubForum->add($subForum);
            $subForum->setForum($this);
        }

        return $this;
    }

    /**
     * Supprime un sous-forum du forum.
     * 
     * @param ForumSubForum $subForum
     * @return static
     */
    public function removeSubForum(ForumSubForum $subForum): static {

        if ($this->SubForum->removeElement($subForum)) {

            // Définit la propriété Forum du sous-forum à null si elle correspond à ce forum
            if ($subForum->getForum() === $this) {

                $subForum->setForum(null);
            }
        }

        return $this;
    }

    /**
     * Retourne le slug du forum.
     * 
     * @return string|null
     */
    public function getSlug(): ?string {

        return $this->Slug;
    }

    /**
     * Définit le slug du forum.
     * 
     * @param string $Slug
     * @return static
     */
    public function setSlug(string $Slug): static {

        $this->Slug = $Slug;

        return $this;
    }

    /**
     * Retourne la position du forum dans l'ordre d'affichage.
     * 
     * @return int|null
     */
    public function getPosition(): ?int {

        return $this->Position;
    }

    /**
     * Définit la position du forum dans l'ordre d'affichage.
     * 
     * @param int $Position
     * @return static
     */
    public function setPosition(int $Position): static {

        $this->Position = $Position;

        return $this;
    }

    /**
     * Retourne la collection des fils de discussion associés à ce forum.
     * 
     * @return Collection<Uuid, ForumThread>
     */
    public function getThread(): Collection {

        return $this->Thread;
    }

    /**
     * Ajoute un fils de discussion au forum.
     * 
     * @param ForumThread $thread
     * @return static
     */
    public function addThread(ForumThread $thread): static {

        if (!$this->Thread->contains($thread)) {

            $this->Thread->add($thread);
            $thread->setForum($this);
        }

        return $this;
    }

    /**
     * Supprime un fils de discussion du forum.
     * 
     * @param ForumThread $thread
     * @return static
     */
    public function removeThread(ForumThread $thread): static {

        if ($this->Thread->removeElement($thread)) {

            // Définit la propriété Forum du fils de discussion à null si elle correspond à ce forum
            if ($thread->getForum() === $this) {

                $thread->setForum(null);
            }
        }

        return $this;
    }

    /**
     * Retourne les rôles autorisés pour accéder à ce forum.
     * 
     * @return array|null
     */
    public function getAuthorizedRoles(): ?array {

        return $this->AuthorizedRoles;
    }

    /**
     * Définit les rôles autorisés pour accéder à ce forum.
     * 
     * @param array|null $AuthorizedRoles
     * @return static
     */
    public function setAuthorizedRoles(?array $AuthorizedRoles): static {

        $this->AuthorizedRoles = $AuthorizedRoles;

        return $this;
    }

    /**
     * Vérifie si le forum a des rôles autorisés définis.
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
