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
use App\Repository\ForumSubForumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Représente un sous-forum dans l'application.
 */
#[ORM\Entity(repositoryClass: ForumSubForumRepository::class)]
class ForumSubForum {

    /**
     * Identifiant unique du sous-forum.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Nom du sous-forum.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    /**
     * Description du sous-forum.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    /**
     * Forum parent auquel ce sous-forum appartient.
     * 
     * @var ForumForum|null
     */
    #[ORM\ManyToOne(inversedBy: 'SubForum')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumForum $Forum = null;

    /**
     * Slug du sous-forum (pour l'URL).
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    /**
     * Position du sous-forum dans l'ordre d'affichage.
     * 
     * @var int|null
     */
    #[ORM\Column(type: "integer", options: ["unsigned" => true, "default" => 0])]
    private ?int $Position = null;

    /**
     * Liste des fils de discussion associés à ce sous-forum.
     * 
     * @var Collection<Uuid, ForumThread>
     */
    #[ORM\OneToMany(targetEntity: ForumThread::class, mappedBy: 'SubForum')]
    private Collection $Thread;

    /**
     * Rôles autorisés pour accéder à ce sous-forum.
     * 
     * @var array|null
     */
    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $AuthorizedRoles = null;

    /**
     * Constructeur de la classe, initialise la collection des fils de discussion.
     */
    public function __construct() {

        $this->Thread = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique du sous-forum.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le nom du sous-forum.
     * 
     * @return string|null
     */
    public function getName(): ?string {

        return $this->Name;
    }

    /**
     * Définit le nom du sous-forum et met à jour le slug si nécessaire.
     * 
     * @param string $Name
     * @return static
     */
    public function setName(string $Name): static {

        $this->Name = $Name;

        // Génère un slug à partir du nom si le slug est vide
        if (empty($this->Slug)) {

            $this->Slug = AutoSlug::toSlugPreserveCase($this->Name);
        }

        return $this;
    }

    /**
     * Retourne la description du sous-forum.
     * 
     * @return string|null
     */
    public function getDescription(): ?string {

        return $this->Description;
    }

    /**
     * Définit la description du sous-forum.
     * 
     * @param string|null $Description
     * @return static
     */
    public function setDescription(?string $Description): static {

        $this->Description = $Description;

        return $this;
    }

    /**
     * Retourne le forum parent auquel ce sous-forum appartient.
     * 
     * @return ForumForum|null
     */
    public function getForum(): ?ForumForum {

        return $this->Forum;
    }

    /**
     * Définit le forum parent auquel ce sous-forum appartient.
     * 
     * @param ForumForum|null $Forum
     * @return static
     */
    public function setForum(?ForumForum $Forum): static {

        $this->Forum = $Forum;

        return $this;
    }

    /**
     * Retourne le slug du sous-forum.
     * 
     * @return string|null
     */
    public function getSlug(): ?string {

        return $this->Slug;
    }

    /**
     * Définit le slug du sous-forum.
     * 
     * @param string $Slug
     * @return static
     */
    public function setSlug(string $Slug): static {

        $this->Slug = $Slug;

        return $this;
    }

    /**
     * Retourne la position du sous-forum dans l'ordre d'affichage.
     * 
     * @return int|null
     */
    public function getPosition(): ?int {

        return $this->Position;
    }

    /**
     * Définit la position du sous-forum dans l'ordre d'affichage.
     * 
     * @param int $Position
     * @return static
     */
    public function setPosition(int $Position): static {

        $this->Position = $Position;

        return $this;
    }

    /**
     * Retourne la collection des fils de discussion associés à ce sous-forum.
     * 
     * @return Collection<Uuid, ForumThread>
     */
    public function getThread(): Collection {

        return $this->Thread;
    }

    /**
     * Ajoute un fils de discussion au sous-forum.
     * 
     * @param ForumThread $thread
     * @return static
     */
    public function addThread(ForumThread $thread): static {

        if (!$this->Thread->contains($thread)) {

            $this->Thread->add($thread);
            $thread->setSubForum($this);
        }

        return $this;
    }

    /**
     * Supprime un fils de discussion du sous-forum.
     * 
     * @param ForumThread $thread
     * @return static
     */
    public function removeThread(ForumThread $thread): static {

        if ($this->Thread->removeElement($thread)) {

            // Définit la propriété SubForum du fils de discussion à null si elle correspond à ce sous-forum
            if ($thread->getSubForum() === $this) {

                $thread->setSubForum(null);
            }
        }

        return $this;
    }

    /**
     * Retourne les rôles autorisés pour accéder à ce sous-forum.
     * 
     * @return array|null
     */
    public function getAuthorizedRoles(): ?array {

        return $this->AuthorizedRoles;
    }

    /**
     * Définit les rôles autorisés pour accéder à ce sous-forum.
     * 
     * @param array|null $AuthorizedRoles
     * @return static
     */
    public function setAuthorizedRoles(?array $AuthorizedRoles): static {

        $this->AuthorizedRoles = $AuthorizedRoles;

        return $this;
    }

    /**
     * Vérifie si le sous-forum a des rôles autorisés définis.
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
