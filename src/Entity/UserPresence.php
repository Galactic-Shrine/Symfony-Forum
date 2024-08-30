<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Enum\UserStatus;
use App\Repository\UserPresenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPresenceRepository::class)]
/**
 * Représente la présence d'un utilisateur et son statut en ligne.
 */
class UserPresence {
    /**
     * Identifiant unique de l'utilisateur.
     * 
     * @var User
     */
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'Presence')]
    #[ORM\JoinColumn(nullable: false)]
    private User $User;

    /**
     * Statut de l'utilisateur.
     * 
     * @var UserStatus
     */
    #[ORM\Column(type: 'string', enumType: UserStatus::class)]
    private UserStatus $Status;

    /**
     * Obtient l'utilisateur.
     * 
     * @return User|null L'utilisateur ou null si non défini.
     */
    public function getUser(): ?User {

        return $this->User;
    }

    /**
     * Définit l'utilisateur.
     * 
     * @param User $User L'utilisateur.
     * @return self
     */
    public function setUser(User $User): self {

        $this->User = $User;
        return $this;
    }

    /**
     * Obtient le statut de l'utilisateur.
     * 
     * @return UserStatus Le statut de l'utilisateur.
     */
    public function getStatus(): UserStatus {

        return $this->Status;
    }

    /**
     * Définit le statut de l'utilisateur.
     * 
     * @param UserStatus $Status Le statut de l'utilisateur.
     * @return self
     */
    public function setStatus(UserStatus $Status): self {

        $this->Status = $Status;
        return $this;
    }
}

