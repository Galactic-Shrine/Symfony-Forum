<?php

namespace App\Entity;

use App\Entity\Category;
use Symfony\Component\Uid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\NewsRepository;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Classe News
 * Représente une entité de nouvelles dans l'application.
 */
#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News {
    
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\Column(type: 'json')]
    private array $Title = [];

    #[ORM\Column(type: 'json')]
    private array $Contents = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $ContentsContinued = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $Slug = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $Category = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Author = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $CreatedAt;

    public function getId(): ?Uuid {
        return $this->Id;
    }

    public function getTitle(): array {
        return $this->Title;
    }

    public function setTitle(array $Title): self {
        $this->Title = $Title;
        return $this;
    }

    public function getContents(): array {
        return $this->Contents;
    }

    public function setContents(array $Contents): self {
        $this->Contents = $Contents;
        return $this;
    }

    public function getContentsContinued(): ?array {
        return $this->ContentsContinued;
    }

    public function setContentsContinued(?array $ContentsContinued): self {
        $this->ContentsContinued = $ContentsContinued;
        return $this;
    }

    public function getSlug(): ?string {
        return $this->Slug;
    }

    public function setSlug(string $Slug): self {
        $this->Slug = $Slug;
        return $this;
    }

    public function getCategory(): ?Category {
        return $this->Category;
    }

    public function setCategory(?Category $Category): self {
        $this->Category = $Category;
        return $this;
    }

    public function getAuthor(): ?User {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self {
        $this->Author = $Author;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeInterface $CreatedAt): self {
        $this->CreatedAt = $CreatedAt;
        return $this;
    }

    public function getTitleByLang(string $lang): ?string {
        return $this->Title[$lang] ?? null;
    }
    
    public function getContentsByLang(string $lang): ?string {
        return $this->Contents[$lang] ?? null;
    }

    public function getContentsContinuedByLang(string $lang): ?string {
        return $this->ContentsContinued[$lang] ?? null;
    }
}
