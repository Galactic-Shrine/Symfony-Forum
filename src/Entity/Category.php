<?php
namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\Column]
    private array $Name = [];

    #[ORM\Column(nullable: true)]
    private ?array $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $Data = null;

    #[ORM\Column]
    private ?int $Position = null;

    public function getId(): ?Uuid
    {
        return $this->Id;
    }

    public function getName(): array
    {
        return $this->Name;
    }

    public function setName(array $Name): static
    {
        $this->Name = $Name;
        return $this;
    }

    public function getDescription(): ?array
    {
        return $this->Description;
    }

    public function setDescription(?array $Description): static
    {
        $this->Description = $Description;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->Slug;
    }

    public function setSlug(string $Slug): static
    {
        $this->Slug = $Slug;
        return $this;
    }

    public function getData(): ?array
    {
        return $this->Data;
    }

    public function setData(?array $Data): static
    {
        $this->Data = $Data;
        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->Position;
    }

    public function setPosition(int $Position): static
    {
        $this->Position = $Position;
        return $this;
    }

    // Ajouter des accesseurs pour les contrôleurs
    public function getControllers(): ?array
    {
        return $this->Data['controllers'] ?? null;
    }

    public function setControllers(?array $controllers): static
    {
        if ($this->Data === null) {
            $this->Data = [];
        }
        $this->Data['controllers'] = $controllers;
        return $this;
    }

    // Ajouter des accesseurs pour l'icône
    public function getIcon(): ?string
    {
        return $this->Data['icon'] ?? null;
    }

    public function setIcon(?string $icon): static
    {
        if ($this->Data === null) {
            $this->Data = [];
        }
        $this->Data['icon'] = $icon;
        return $this;
    }

    // Ajouter des accesseurs pour la bannière
    public function getBanner(): ?string
    {
        return $this->Data['banner'] ?? null;
    }

    public function setBanner(?string $banner): static
    {
        if ($this->Data === null) {
            $this->Data = [];
        }
        $this->Data['banner'] = $banner;
        return $this;
    }

    // Ajouter des accesseurs pour l'image de catégorie
    public function getCategoryImage(): ?string
    {
        return $this->Data['category_image'] ?? null;
    }

    public function setCategoryImage(?string $categoryImage): static
    {
        if ($this->Data === null) {
            $this->Data = [];
        }
        $this->Data['category_image'] = $categoryImage;
        return $this;
    }

    public function getNameByLang(string $lang): ?string {
        return $this->Name[$lang] ?? null;
    }
    
    public function getDescriptionByLang(string $lang): ?string {
        return $this->Description[$lang] ?? null;
    }
}
