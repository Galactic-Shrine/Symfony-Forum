<?php

namespace App\Entity;

use App\Enum\UserStatus;
use App\Repository\UserPresenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserPresenceRepository::class)]
class UserPresence
{
    #[ORM\Id]
    #[ORM\OneToOne(targetEntity: User::class, inversedBy: 'Presence')]
    #[ORM\JoinColumn(nullable: false)]
    private User $User;

    #[ORM\Column(type: 'string', enumType: UserStatus::class)]
    private UserStatus $Status;

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;
        return $this;
    }

    public function getStatus(): UserStatus
    {
        return $this->Status;
    }

    public function setStatus(UserStatus $Status): self
    {
        $this->Status = $Status;
        return $this;
    }
}
