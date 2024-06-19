<?php

namespace App\Entity;

use App\Entity\ForumPost;
use App\Repository\ForumPostReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ForumPostReportRepository::class)]
class ForumPostReport
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\ManyToOne(inversedBy: 'Report')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumPost $Post = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column]
    private ?bool $Processed = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreateAt = null;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function isProcessed(): ?bool
    {
        return $this->Processed;
    }

    public function setProcessed(bool $Processed): static
    {
        $this->Processed = $Processed;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->CreateAt;
    }

    public function setCreateAt(\DateTimeImmutable $CreateAt): static
    {
        $this->CreateAt = $CreateAt;

        return $this;
    }
}
