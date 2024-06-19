<?php

namespace App\Entity;

use App\Entity\User;
use App\Repository\ForumThreadRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: ForumThreadRepository::class)]
class ForumThread
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\ManyToOne(inversedBy: 'Thread')]
    private ?ForumForum $Forum = null;

    #[ORM\ManyToOne(inversedBy: 'Thread')]
    private ?ForumSubForum $SubForum = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(length: 510, nullable: true)]
    private ?string $SubTitle = null;

    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    #[ORM\Column(nullable: true)]
    private ?bool $IsPin = null;
    
    #[ORM\Column(nullable: true)]
    private ?bool $IsResolved = null;

    #[ORM\Column(nullable: true)]
    private ?bool $IsLocked = null;

    #[ORM\OneToOne(inversedBy: 'ForumThread', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: false)]
    private ?User $Author = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreateAt = null;

    #[ORM\Column]
    private ?int $NumberReplies = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $RepliesCreateAt = null;

    /**
     * @var Collection<int, ForumPost>
     */
    #[ORM\OneToMany(targetEntity: ForumPost::class, mappedBy: 'Thread', cascade: ['persist', 'remove'])]
    private Collection $Post;

    public function __construct()
    {
        $this->Post = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->Id;
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

    public function getSubForum(): ?ForumSubForum
    {
        return $this->SubForum;
    }

    public function setSubForum(?ForumSubForum $SubForum): static
    {
        $this->SubForum = $SubForum;
        
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;
        
        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->SubTitle;
    }

    public function setSubTitle(?string $SubTitle): static
    {
        $this->SubTitle = $SubTitle;
        
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

    public function isIsPin(): ?bool
    {
        return $this->IsPin;
    }

    public function setIsPin(?bool $IsPin): static
    {
        $this->IsPin = $IsPin;
        
        return $this;
    }

    public function isIsResolved(): ?bool
    {
        return $this->IsResolved;
    }

    public function setIsResolved(?bool $IsResolved): static
    {
        $this->IsResolved = $IsResolved;

        return $this;
    }

    public function isIsLocked(): ?bool
    {
        return $this->IsLocked;
    }

    public function setIsLocked(?bool $IsLocked): static
    {
        $this->IsLocked = $IsLocked;
        
        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(User $Author): static
    {
        $this->Author = $Author;
        
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

    public function getNumberReplies(): ?int
    {
        return $this->NumberReplies;
    }

    public function setNumberReplies(int $NumberReplies): static
    {
        $this->NumberReplies = $NumberReplies;
        
        return $this;
    }

    public function getRepliesCreateAt(): ?\DateTimeImmutable
    {
        return $this->RepliesCreateAt;
    }

    public function setRepliesCreateAt(?\DateTimeImmutable $RepliesCreateAt): static
    {
        $this->RepliesCreateAt = $RepliesCreateAt;
        
        return $this;
    }

    /**
     * @return Collection<int, ForumPost>
     */
    public function getPost(): Collection
    {
        return $this->Post;
    }

    public function addPost(ForumPost $post): static
    {
        if (!$this->Post->contains($post)) {
            $this->Post->add($post);
            $post->setThread($this);
        }

        return $this;
    }

    public function removePost(ForumPost $post): static
    {
        if ($this->Post->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getThread() === $this) {
                $post->setThread(null);
            }
        }

        return $this;
    }
}
