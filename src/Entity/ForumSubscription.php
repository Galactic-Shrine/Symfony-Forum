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
use App\Entity\ForumThread;
use App\Repository\ForumSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Représente une souscription d'un utilisateur à un fil de discussion.
 */
#[ORM\Entity(repositoryClass: ForumSubscriptionRepository::class)]
class ForumSubscription {

    /**
     * Identifiant unique de la souscription.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Fil de discussion auquel l'utilisateur est abonné.
     * 
     * @var ForumThread|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName: "id", nullable: true)]
    private ?ForumThread $Thread = null;

    /**
     * Utilisateur qui est abonné au fil de discussion.
     * 
     * @var User|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName: "id", nullable: true)]
    private ?User $User = null;

    /**
     * Constructeur de la classe, initialise la souscription avec un fil de discussion et un utilisateur.
     * 
     * @param ForumThread $thread Le fil de discussion auquel l'utilisateur s'abonne.
     * @param User $user L'utilisateur qui s'abonne au fil de discussion.
     */
    public function __construct(ForumThread $thread, User $user) {

        $this->setThread($thread);
        $this->setUser($user);
    }

    /**
     * Retourne l'identifiant unique de la souscription.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le fil de discussion auquel l'utilisateur est abonné.
     * 
     * @return ForumThread|null
     */
    public function getThread(): ?ForumThread {

        return $this->Thread;
    }

    /**
     * Définit le fil de discussion auquel l'utilisateur est abonné.
     * 
     * @param ForumThread|null $Thread
     * @return static
     */
    public function setThread(?ForumThread $Thread): static {

        $this->Thread = $Thread;

        return $this;
    }

    /**
     * Retourne l'utilisateur qui est abonné au fil de discussion.
     * 
     * @return User|null
     */
    public function getUser(): ?User {

        return $this->User;
    }

    /**
     * Définit l'utilisateur qui est abonné au fil de discussion.
     * 
     * @param User|null $User
     * @return static
     */
    public function setUser(?User $User): static {
        
        $this->User = $User;

        return $this;
    }
}
