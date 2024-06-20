<?php

namespace App\Entity;

use App\Enum\UserStatus as UserStatusEnum;
use App\Repository\UserStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatusRepository::class)]
class UserStatus
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'Status', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column(type: 'string', enumType: UserStatusEnum::class)]
    private ?UserStatusEnum $Status = null; // Utilisation du type enum 'UserStatusEnum'

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getStatus(): ?UserStatusEnum
    {
        return $this->Status;
    }

    public function setStatus(?UserStatusEnum $Status): static
    {
        $this->Status = $Status;

        return $this;
    }
}
