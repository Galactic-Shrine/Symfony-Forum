<?php

namespace App\Entity;

use App\Config\AutoSlug;
use App\Entity\ForumThread;
use App\Entity\ForumSubForum;
use App\Repository\ForumForumRepository; 
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: ForumForumRepository::class)]
class ForumForum
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Description = null;

    #[ORM\ManyToOne(inversedBy: 'Forum')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ForumCategory $Category = null;

    #[ORM\OneToMany(targetEntity: ForumSubForum::class, mappedBy: 'Forum', orphanRemoval: true)]
    private Collection $SubForum;

    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    #[ORM\Column(type: "integer", options: ["unsigned" => true, "default" => 0])]
    private ?int $Position = null;

    #[ORM\OneToMany(targetEntity: ForumThread::class, mappedBy: 'Forum')]
    private Collection $Thread;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $AuthorizedRoles = null;

    public function __construct()
    {
        $this->SubForum = new ArrayCollection();
        $this->Thread = new ArrayCollection();
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

        if(empty($this->Slug)) { 
			
			$this->Slug = AutoSlug::convert($this->Name); 
		}

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

    public function getCategory(): ?ForumCategory
    {
        return $this->Category;
    }

    public function setCategory(?ForumCategory $Category): static
    {
        $this->Category = $Category;

        return $this;
    }

    /**
     * @return Collection<Uuid, ForumSubForum>
     */
    public function getSubForum(): Collection
    {
        return $this->SubForum;
    }

    public function addSubForum(ForumSubForum $subForum): static
    {
        if (!$this->SubForum->contains($subForum)) {
            $this->SubForum->add($subForum);
            $subForum->setForum($this);
        }

        return $this;
    }

    public function removeSubForum(ForumSubForum $subForum): static
    {
        if ($this->SubForum->removeElement($subForum)) {
            // set the owning side to null (unless already changed)
            if ($subForum->getForum() === $this) {
                $subForum->setForum(null);
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

    /**
     * @return Collection<Uuid, ForumThread>
     */
    public function getThread(): Collection
    {
        return $this->Thread;
    }

    public function addThread(ForumThread $thread): static
    {
        if (!$this->Thread->contains($thread)) {
            $this->Thread->add($thread);
            $thread->setForum($this);
        }

        return $this;
    }

    public function removeThread(ForumThread $thread): static
    {
        if ($this->Thread->removeElement($thread)) {
            // set the owning side to null (unless already changed)
            if ($thread->getForum() === $this) {
                $thread->setForum(null);
            }
        }

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
