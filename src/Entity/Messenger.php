<?php

namespace App\Entity;

use App\Repository\MessengerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity(repositoryClass: MessengerRepository::class)]
class Messenger
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\ManyToOne(inversedBy: 'MessegerSent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Sender = null;

    #[ORM\ManyToOne(inversedBy: 'MessegerReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Recipient = null;

    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Message = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    #[ORM\Column]
    private ?bool $IsRead = null;

    public function __construct() {
    
        $this->CreateAt = new \DateTimeImmutable;
    }

    public function getId(): ?Uuid
    {
        return $this->Id;
    }

    public function getSender(): ?User
    {
        return $this->Sender;
    }

    public function setSender(?User $Sender): static
    {
        $this->Sender = $Sender;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->Recipient;
    }

    public function setRecipient(?User $Recipient): static
    {
        $this->Recipient = $Recipient;

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

    public function getMessage(): ?string
    {
        return $this->Message;
    }

    public function setMessage(string $Message): static
    {
        $this->Message = $Message;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->CreatedAt;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    public function isRead(): ?bool
    {
        return $this->IsRead;
    }

    public function setRead(bool $IsRead): static
    {
        $this->IsRead = $IsRead;

        return $this;
    }
}
