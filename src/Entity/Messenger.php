<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Repository\MessengerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\Types\UuidType;

/**
 * Représente un message échangé entre utilisateurs.
 */
#[ORM\Entity(repositoryClass: MessengerRepository::class)]
class Messenger {

    /**
     * Identifiant unique du message.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Utilisateur ayant envoyé le message.
     * 
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'MessegerSent')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Sender = null;

    /**
     * Utilisateur recevant le message.
     * 
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'MessegerReceived')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Recipient = null;

    /**
     * Titre du message.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $Title = null;

    /**
     * Contenu du message.
     * 
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $Message = null;

    /**
     * Date et heure de création du message.
     * 
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $CreatedAt = null;

    /**
     * Indique si le message a été lu.
     * 
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $IsRead = null;

    /**
     * Constructeur pour initialiser la date de création.
     */
    public function __construct() {

        $this->CreatedAt = new \DateTimeImmutable();
    }

    /**
     * Retourne l'identifiant unique du message.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne l'utilisateur ayant envoyé le message.
     * 
     * @return User|null
     */
    public function getSender(): ?User {

        return $this->Sender;
    }

    /**
     * Définit l'utilisateur ayant envoyé le message.
     * 
     * @param User|null $Sender
     * @return static
     */
    public function setSender(?User $Sender): static {

        $this->Sender = $Sender;

        return $this;
    }

    /**
     * Retourne l'utilisateur recevant le message.
     * 
     * @return User|null
     */
    public function getRecipient(): ?User {

        return $this->Recipient;
    }

    /**
     * Définit l'utilisateur recevant le message.
     * 
     * @param User|null $Recipient
     * @return static
     */
    public function setRecipient(?User $Recipient): static {

        $this->Recipient = $Recipient;

        return $this;
    }

    /**
     * Retourne le titre du message.
     * 
     * @return string|null
     */
    public function getTitle(): ?string {

        return $this->Title;
    }

    /**
     * Définit le titre du message.
     * 
     * @param string $Title
     * @return static
     */
    public function setTitle(string $Title): static {

        $this->Title = $Title;

        return $this;
    }

    /**
     * Retourne le contenu du message.
     * 
     * @return string|null
     */
    public function getMessage(): ?string {

        return $this->Message;
    }

    /**
     * Définit le contenu du message.
     * 
     * @param string $Message
     * @return static
     */
    public function setMessage(string $Message): static {

        $this->Message = $Message;

        return $this;
    }

    /**
     * Retourne la date et l'heure de création du message.
     * 
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable {

        return $this->CreatedAt;
    }

    /**
     * Définit la date et l'heure de création du message.
     * 
     * @param \DateTimeImmutable $CreatedAt
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static {

        $this->CreatedAt = $CreatedAt;

        return $this;
    }

    /**
     * Indique si le message a été lu.
     * 
     * @return bool|null
     */
    public function isRead(): ?bool {

        return $this->IsRead;
    }

    /**
     * Définit si le message a été lu.
     * 
     * @param bool $IsRead
     * @return static
     */
    public function setRead(bool $IsRead): static {

        $this->IsRead = $IsRead;
        
        return $this;
    }
}
