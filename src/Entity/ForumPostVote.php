<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Repository\ForumPostVoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Représente un vote sur un message dans le forum.
 */
#[ORM\Entity(repositoryClass: ForumPostVoteRepository::class)]
class ForumPostVote {

    /**
     * Valeur constante représentant un vote positif (upvote).
     * 
     * @var int
     */
    public const VOTE_UP = +1;

    /**
     * Valeur constante représentant un vote négatif (downvote).
     * 
     * @var int
     */
    public const VOTE_DOWN = -1;

    /**
     * Identifiant unique du vote.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Message sur lequel le vote est exprimé.
     * 
     * @var ForumPost|null
     */
    #[ORM\ManyToOne(inversedBy: 'Vote')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumPost $Post = null;

    /**
     * Fil de discussion associé au message.
     * 
     * @var ForumThread|null
     */
    #[ORM\ManyToOne]
    private ?ForumThread $Thread = null;

    /**
     * Type de vote (VOTE_UP ou VOTE_DOWN).
     * 
     * @var int|null
     */
    #[ORM\Column]
    private ?int $VoteType = null;

    /**
     * Utilisateur qui a exprimé le vote.
     * 
     * @var User|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    /**
     * Retourne l'identifiant unique du vote.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le message sur lequel le vote est exprimé.
     * 
     * @return ForumPost|null
     */
    public function getPost(): ?ForumPost {

        return $this->Post;
    }

    /**
     * Définit le message sur lequel le vote est exprimé.
     * 
     * @param ForumPost|null $Post Message.
     * @return static
     */
    public function setPost(?ForumPost $Post): static {

        $this->Post = $Post;

        return $this;
    }

    /**
     * Retourne le fil de discussion associé au message.
     * 
     * @return ForumThread|null
     */
    public function getThread(): ?ForumThread {

        return $this->Thread;
    }

    /**
     * Définit le fil de discussion associé au message.
     * 
     * @param ForumThread|null $Thread Fil de discussion.
     * @return static
     */
    public function setThread(?ForumThread $Thread): static {

        $this->Thread = $Thread;

        return $this;
    }

    /**
     * Retourne le type de vote (VOTE_UP ou VOTE_DOWN).
     * 
     * @return int|null
     */
    public function getVoteType(): ?int {

        return $this->VoteType;
    }

    /**
     * Définit le type de vote (VOTE_UP ou VOTE_DOWN).
     * 
     * @param int $VoteType Type de vote.
     * @return static
     */
    public function setVoteType(int $VoteType): static {

        $this->VoteType = $VoteType;

        return $this;
    }

    /**
     * Retourne l'utilisateur qui a exprimé le vote.
     * 
     * @return User|null
     */
    public function getUser(): ?User {

        return $this->User;
    }

    /**
     * Définit l'utilisateur qui a exprimé le vote.
     * 
     * @param User|null $User Utilisateur.
     * @return static
     */
    public function setUser(?User $User): static {
        
        $this->User = $User;

        return $this;
    }
}
