<?php

namespace App\Entity;

use App\Entity\ForumForum;
use App\Repository\ForumCategoryRepository;
use Doctrine\ORM\Mapping as ORM; 
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: ForumCategoryRepository::class)]
class ForumCategory
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $Description = null;

    #[ORM\OneToMany(targetEntity: ForumForum::class, mappedBy: 'Category')]
    private Collection $Forum;

    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    #[ORM\Column(type: "integer", options: ["unsigned" => true, "default" => 0])]
    private ?int $Position = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $AuthorizedRoles = null;

    public function __construct()
    {
        $this->Forum = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->Id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): static
    {
        $this->Description = $Description;

        return $this;
    }

        /**
     * @return Collection<Uuid, ForumForum>
     */
    public function getForum(): Collection
    {
        return $this->Forum;
    }

    public function addForum(ForumForum $forum): static
    {
        if (!$this->Forum->contains($forum)) {
            $this->Forum->add($forum);
            $forum->setCategory($this);
        }

        return $this;
    }

    public function removeForum(ForumForum $forum): static
    {
        if ($this->Forum->removeElement($forum)) {
            // set the owning side to null (unless already changed)
            if ($forum->getCategory() === $this) {
                $forum->setCategory(null);
            }
        }

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

    public function getPosition(): ?int
    {
        return $this->Position;
    }

    public function setPosition(int $Position): static
    {
        $this->Position = $Position;

        return $this;
    }

    public function getAuthorizedRoles(): ?array
    {
        return $this->AuthorizedRoles;
    }

    public function setAuthorizedRoles(?array $AuthorizedRoles): static
    {
        $this->AuthorizedRoles = $AuthorizedRoles;

        return $this;
    }

    public function hasAuthorizedRoles(): bool
    {

        if(!is_null($this->AuthorizedRoles) && count($this->AuthorizedRoles) >= 1 && !empty($this->AuthorizedRoles[0])){

            return true;
        }

        return false;
    }
}
