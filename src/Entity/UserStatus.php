<?php

namespace App\Entity;

use App\Enum\UserStatus as StatusEnum;
use App\Repository\UserStatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserStatusRepository::class)]
class UserStatus
{
    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'Status', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column(type: 'string', enumType: StatusEnum::class)]
    private $Status;

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getStatus(): StatusEnum
    {
        return $this->Status;
    }

    public function setStatus(StatusEnum $Status): self
    {
        $this->Status = $Status;

        return $this;
    }
}
