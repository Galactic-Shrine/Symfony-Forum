<?php

namespace App\Entity;

use App\Repository\ForumPostRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ForumPostRepository::class)]
class ForumPost
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * @var Collection<Uuid, File>
     */
    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'Post', cascade: ['persist', 'remove'])]
    private Collection $Files;

    #[ORM\ManyToOne(inversedBy: 'Post')]
    private ?ForumThread $Thread = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Content = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: true)]
    private ?User $User = null;

    #[ORM\Column(length: 255)]
    private ?string $Ip = null; // POUR DES RAISONS JURIDIQUES ET DE SÉCURITÉ

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ModerateReason = null;

    /**
     * @var Collection<int, ForumPostReport>
     */
    #[ORM\OneToMany(targetEntity: ForumPostReport::class, mappedBy: 'Post', orphanRemoval: true)]
    private Collection $Report;

    /**
     * @var Collection<int, ForumPostVote>
     */
    #[ORM\OneToMany(targetEntity: ForumPostVote::class, mappedBy: 'Post', orphanRemoval: true)]
    private Collection $Vote;

    #[ORM\Column(nullable: true)]
    private ?int $VoteUp = null;

    public function __construct()
    {
        $this->Files = new ArrayCollection();
        $this->Report = new ArrayCollection();
        $this->Vote = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->Id;
    }

    /**
     * @return Collection<Uuid, File>
     */
    public function getFiles(): Collection
    {
        return $this->Files;
    }

    public function addFile(File $file): static
    {
        if (!$this->Files->contains($file)) {
            $this->Files->add($file);
            $file->setPost($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->Files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getPost() === $this) {
                $file->setPost(null);
            }
        }

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

    public function getContent(): ?string
    {
        return $this->Content;
    }

    public function setContent(string $Content): static
    {
        $this->Content = $Content;

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

    public function getIp(): ?string
    {
        return $this->Ip;
    }

    public function setIp(string $Ip): static
    {
        $this->Ip = $Ip;

        return $this;
    }

    public function getModerateReason(): ?string
    {
        return $this->ModerateReason;
    }

    public function setModerateReason(?string $ModerateReason): static
    {
        $this->ModerateReason = $ModerateReason;

        return $this;
    }

    /**
     * @return Collection<int, ForumPostReport>
     */
    public function getReport(): Collection
    {
        return $this->Report;
    }

    public function addReport(ForumPostReport $report): static
    {
        if (!$this->Report->contains($report)) {
            $this->Report->add($report);
            $report->setPost($this);
        }

        return $this;
    }

    public function removeReport(ForumPostReport $report): static
    {
        if ($this->Report->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPost() === $this) {
                $report->setPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ForumPostVote>
     */
    public function getVote(): Collection
    {
        return $this->Vote;
    }

    public function addVote(ForumPostVote $vote): static
    {
        if (!$this->Vote->contains($vote)) {
            $this->Vote->add($vote);
            $vote->setPost($this);
        }

        return $this;
    }

    public function removeVote(ForumPostVote $vote): static
    {
        if ($this->Vote->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getPost() === $this) {
                $vote->setPost(null);
            }
        }

        return $this;
    }

    public function getVoteUp(): ?int
    {
        return $this->VoteUp;
    }

    public function setVoteUp(?int $VoteUp): static
    {
        $this->VoteUp = $VoteUp;

        return $this;
    }
}
