<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Entity\User;
use App\Repository\ForumThreadRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Représente un fil de discussion dans un forum.
 */
#[ORM\Entity(repositoryClass: ForumThreadRepository::class)]
class ForumThread {

    /**
     * Identifiant unique du fil de discussion.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Forum auquel ce fil de discussion appartient.
     * 
     * @var ForumForum|null
     */
    #[ORM\ManyToOne(inversedBy: 'Thread')]
    private ?ForumForum $Forum = null;

    /**
     * Sous-forum auquel ce fil de discussion appartient.
     * 
     * @var ForumSubForum|null
     */
    #[ORM\ManyToOne(inversedBy: 'Thread')]
    private ?ForumSubForum $SubForum = null;

    /**
     * Titre du fil de discussion.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    /**
     * Sous-titre du fil de discussion.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 510, nullable: true)]
    private ?string $SubTitle = null;

    /**
     * Slug du fil de discussion (pour l'URL).
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    /**
     * Indique si le fil de discussion est épinglé.
     * 
     * @var bool|null
     */
    #[ORM\Column(nullable: true)]
    private ?bool $IsPin = null;
    
    /**
     * Indique si le fil de discussion est résolu.
     * 
     * @var bool|null
     */
    #[ORM\Column(nullable: true)]
    private ?bool $IsResolved = null;

    /**
     * Indique si le fil de discussion est verrouillé.
     * 
     * @var bool|null
     */
    #[ORM\Column(nullable: true)]
    private ?bool $IsLocked = null;

    /**
     * Auteur du fil de discussion.
     * 
     * @var User|null
     */
    #[ORM\OneToOne(inversedBy: 'ForumThread', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?User $Author = null;

    /**
     * Date de création du fil de discussion.
     * 
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $CreateAt = null;

    /**
     * Nombre de réponses dans le fil de discussion.
     * 
     * @var int|null
     */
    #[ORM\Column]
    private ?int $NumberReplies = null;

    /**
     * Date de la dernière réponse dans le fil de discussion.
     * 
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $RepliesCreateAt = null;

    /**
     * Liste des messages associés à ce fil de discussion.
     * 
     * @var Collection<int, ForumPost>
     */
    #[ORM\OneToMany(targetEntity: ForumPost::class, mappedBy: 'Thread', cascade: ['persist', 'remove'])]
    private Collection $Post;

    /**
     * Constructeur de la classe, initialise la collection des messages.
     */
    public function __construct() {

        $this->Post = new ArrayCollection();
    }

    /**
     * Retourne l'identifiant unique du fil de discussion.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le forum auquel ce fil de discussion appartient.
     * 
     * @return ForumForum|null
     */
    public function getForum(): ?ForumForum {

        return $this->Forum;
    }

    /**
     * Définit le forum auquel ce fil de discussion appartient.
     * 
     * @param ForumForum|null $Forum
     * @return static
     */
    public function setForum(?ForumForum $Forum): static {

        $this->Forum = $Forum;
        
        return $this;
    }

    /**
     * Retourne le sous-forum auquel ce fil de discussion appartient.
     * 
     * @return ForumSubForum|null
     */
    public function getSubForum(): ?ForumSubForum {

        return $this->SubForum;
    }

    /**
     * Définit le sous-forum auquel ce fil de discussion appartient.
     * 
     * @param ForumSubForum|null $SubForum
     * @return static
     */
    public function setSubForum(?ForumSubForum $SubForum): static {

        $this->SubForum = $SubForum;
        
        return $this;
    }

    /**
     * Retourne le titre du fil de discussion.
     * 
     * @return string|null
     */
    public function getTitle(): ?string {

        return $this->Title;
    }

    /**
     * Définit le titre du fil de discussion.
     * 
     * @param string $Title
     * @return static
     */
    public function setTitle(string $Title): static {

        $this->Title = $Title;
        
        return $this;
    }

    /**
     * Retourne le sous-titre du fil de discussion.
     * 
     * @return string|null
     */
    public function getSubTitle(): ?string {

        return $this->SubTitle;
    }

    /**
     * Définit le sous-titre du fil de discussion.
     * 
     * @param string|null $SubTitle
     * @return static
     */
    public function setSubTitle(?string $SubTitle): static {

        $this->SubTitle = $SubTitle;
        
        return $this;
    }

    /**
     * Retourne le slug du fil de discussion.
     * 
     * @return string|null
     */
    public function getSlug(): ?string {

        return $this->Slug;
    }

    /**
     * Définit le slug du fil de discussion.
     * 
     * @param string $Slug
     * @return static
     */
    public function setSlug(string $Slug): static {

        $this->Slug = $Slug;
        
        return $this;
    }

    /**
     * Vérifie si le fil de discussion est épinglé.
     * 
     * @return bool|null
     */
    public function isIsPin(): ?bool {

        return $this->IsPin;
    }

    /**
     * Définit si le fil de discussion est épinglé.
     * 
     * @param bool|null $IsPin
     * @return static
     */
    public function setIsPin(?bool $IsPin): static {

        $this->IsPin = $IsPin;
        
        return $this;
    }

    /**
     * Vérifie si le fil de discussion est résolu.
     * 
     * @return bool|null
     */
    public function isIsResolved(): ?bool {

        return $this->IsResolved;
    }

    /**
     * Définit si le fil de discussion est résolu.
     * 
     * @param bool|null $IsResolved
     * @return static
     */
    public function setIsResolved(?bool $IsResolved): static {

        $this->IsResolved = $IsResolved;

        return $this;
    }

    /**
     * Vérifie si le fil de discussion est verrouillé.
     * 
     * @return bool|null
     */
    public function isIsLocked(): ?bool {

        return $this->IsLocked;
    }

    /**
     * Définit si le fil de discussion est verrouillé.
     * 
     * @param bool|null $IsLocked
     * @return static
     */
    public function setIsLocked(?bool $IsLocked): static {

        $this->IsLocked = $IsLocked;
        
        return $this;
    }

    /**
     * Retourne l'auteur du fil de discussion.
     * 
     * @return User|null
     */
    public function getAuthor(): ?User {

        return $this->Author;
    }

    /**
     * Définit l'auteur du fil de discussion.
     * 
     * @param User $Author
     * @return static
     */
    public function setAuthor(User $Author): static {

        $this->Author = $Author;
        
        return $this;
    }

    /**
     * Retourne la date de création du fil de discussion.
     * 
     * @return \DateTimeImmutable|null
     */
    public function getCreateAt(): ?\DateTimeImmutable {

        return $this->CreateAt;
    }

    /**
     * Définit la date de création du fil de discussion.
     * 
     * @param \DateTimeImmutable $CreateAt
     * @return static
     */
    public function setCreateAt(\DateTimeImmutable $CreateAt): static {

        $this->CreateAt = $CreateAt;
        
        return $this;
    }

    /**
     * Retourne le nombre de réponses dans le fil de discussion.
     * 
     * @return int|null
     */
    public function getNumberReplies(): ?int {

        return $this->NumberReplies;
    }

    /**
     * Définit le nombre de réponses dans le fil de discussion.
     * 
     * @param int $NumberReplies
     * @return static
     */
    public function setNumberReplies(int $NumberReplies): static {

        $this->NumberReplies = $NumberReplies;
        
        return $this;
    }

    /**
     * Retourne la date de la dernière réponse dans le fil de discussion.
     * 
     * @return \DateTimeImmutable|null
     */
    public function getRepliesCreateAt(): ?\DateTimeImmutable {

        return $this->RepliesCreateAt;
    }

    /**
     * Définit la date de la dernière réponse dans le fil de discussion.
     * 
     * @param \DateTimeImmutable|null $RepliesCreateAt
     * @return static
     */
    public function setRepliesCreateAt(?\DateTimeImmutable $RepliesCreateAt): static {

        $this->RepliesCreateAt = $RepliesCreateAt;
        
        return $this;
    }

    /**
     * Retourne la collection des messages associés à ce fil de discussion.
     * 
     * @return Collection<int, ForumPost>
     */
    public function getPost(): Collection {

        return $this->Post;
    }

    /**
     * Ajoute un message au fil de discussion.
     * 
     * @param ForumPost $post
     * @return static
     */
    public function addPost(ForumPost $post): static {

        if (!$this->Post->contains($post)) {

            $this->Post->add($post);
            $post->setThread($this);
        }

        return $this;
    }

    /**
     * Supprime un message du fil de discussion.
     * 
     * @param ForumPost $post
     * @return static
     */
    public function removePost(ForumPost $post): static {

        if ($this->Post->removeElement($post)) {

            // Définit la propriété Thread du message à null si elle correspond à ce fil de discussion
            if ($post->getThread() === $this) {
                
                $post->setThread(null);
            }
        }

        return $this;
    }
}
