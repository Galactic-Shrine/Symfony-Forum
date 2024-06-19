<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\ForumThread;
use App\Repository\ForumSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ForumSubscriptionRepository::class)]
class ForumSubscription
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName:"id", nullable:true)]
    private ?ForumThread $Thread = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName:"id", nullable:true)]
    private ?User $User = null;

    public function __construct(ForumThread $thread, User $user)
    {
        $this->setThread($thread);
        $this->setUser($user);
    }

    public function getId(): ?Uuid {
    
        return $this->Id;
    }

    public function getThread(): ?ForumThread
    {
        return $this->Thread;
    }

    public function setThread(?ForumThread $Thread): static
    {
        $this->Thread = $Thread;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }
}
