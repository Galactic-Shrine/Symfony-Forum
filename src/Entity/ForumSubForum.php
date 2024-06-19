<?php

namespace App\Entity;

use App\Config\AutoSlug;
use App\Entity\ForumThread;
use App\Repository\ForumSubForumRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: ForumSubForumRepository::class)]
class ForumSubForum
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

	#[ORM\ManyToOne(inversedBy: 'SubForum')]
	#[ORM\JoinColumn(nullable: false)]
	private ?ForumForum $Forum = null;

	#[ORM\Column(length: 255)]
	private ?string $Slug = null;

	#[ORM\Column(type: "integer", options: ["unsigned" => true, "default" => 0])]
	private ?int $Position = null;

	#[ORM\OneToMany(targetEntity: ForumThread::class, mappedBy: 'SubForum')]
	private Collection $Thread;

	#[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $AuthorizedRoles = null;

	public function __construct()
	{
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

	public function getForum(): ?ForumForum
	{
		return $this->Forum;
	}

	public function setForum(?ForumForum $Forum): static
	{
		$this->Forum = $Forum;

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
			$thread->setSubForum($this);
		}

		return $this;
	}

	public function removeThread(ForumThread $thread): static
	{
		if ($this->Thread->removeElement($thread)) {
			// set the owning side to null (unless already changed)
			if ($thread->getSubForum() === $this) {
				$thread->setSubForum(null);
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
