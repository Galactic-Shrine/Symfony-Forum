<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\Column(length: 245)]
    private ?string $Filename = null;

    #[ORM\Column(length: 245)]
    private ?string $OriginalName = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $Path = null;

    #[ORM\Column(length: 10)]
    private ?string $Extension = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $Size = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $UploadAt = null;

    #[ORM\ManyToOne(inversedBy: 'Files')]
    private ?ForumPost $Post = null;

    public function __construct() {
        $this->UploadAt = new \DateTimeImmutable;
    }

    public function getId(): ?Uuid
    {
        return $this->Id;
    }

    public function getFilename(): ?string
    {
        return $this->Filename;
    }

    public function setFilename(string $Filename): static
    {
        $this->Filename = $Filename;

        return $this;
    }

    public function getOriginalName(): ?string
    {
        return $this->OriginalName;
    }

    public function setOriginalName(string $OriginalName): static
    {
        $this->OriginalName = $OriginalName;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->Path;
    }

    public function setPath(string $Path): static
    {
        $this->Path = $Path;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->Extension;
    }

    public function setExtension(string $Extension): static
    {
        $this->Extension = $Extension;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->Size;
    }

    public function setSize(string $Size): static
    {
        $this->Size = $Size;

        return $this;
    }

    public function getUploadAt(): ?\DateTimeImmutable
    {
        return $this->UploadAt;
    }

    public function setUploadAt(\DateTimeImmutable $UploadAt): static
    {
        $this->UploadAt = $UploadAt;

        return $this;
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
}
