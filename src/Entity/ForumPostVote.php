<?php

namespace App\Entity;

use App\Repository\ForumPostVoteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ForumPostVoteRepository::class)]
class ForumPostVote
{
    const VOTE_UP = +1;
    const VOTE_DOWN = -1;

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\ManyToOne(inversedBy: 'Vote')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumPost $Post = null;

    #[ORM\ManyToOne]
    private ?ForumThread $Thread = null;

    #[ORM\Column]
    private ?int $VoteType = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    public function getId(): ?Uuid
    {
        return $this->Id;
    }

    public function getPost(): ?ForumPost
    {
        return $this->Post;
    }

    public function setPost(?ForumPost $Post): static
    {
        $this->Post = $Post;

        return $this;
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

    public function getVoteType(): ?int
    {
        return $this->VoteType;
    }

    public function setVoteType(int $VoteType): static
    {
        $this->VoteType = $VoteType;

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
