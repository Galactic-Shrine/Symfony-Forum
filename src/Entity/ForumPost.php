<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Repository\ForumPostRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

/**
 * Représente un message ou un poste dans un fil de discussion.
 */
#[ORM\Entity(repositoryClass: ForumPostRepository::class)]
class ForumPost {
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
     * Liste des fichiers joints au message.
     * 
     * @var Collection<Uuid, File>
     */
    #[ORM\OneToMany(targetEntity: File::class, mappedBy: 'Post', cascade: ['persist', 'remove'])]
    private Collection $Files;

    /**
     * Fil de discussion auquel ce message appartient.
     * 
     * @var ForumThread|null
     */
    #[ORM\ManyToOne(inversedBy: 'Post')]
    private ?ForumThread $Thread = null;

    /**
     * Contenu textuel du message.
     * 
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $Content = null;

    /**
     * Utilisateur ayant posté ce message.
     * 
     * @var User|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(referencedColumnName: 'id', nullable: true)]
    private ?User $User = null;

    /**
     * Adresse IP de l'auteur du message.
     * 
     * @var string
     * @note Utilisé pour des raisons juridiques et de sécurité.
     */
    #[ORM\Column(length: 255)]
    private ?string $Ip = null;

    /**
     * Raison de la modération du message, si applicable.
     * 
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ModerateReason = null;

    /**
     * Liste des rapports concernant ce message.
     * 
     * @var Collection<int, ForumPostReport>
     */
    #[ORM\OneToMany(targetEntity: ForumPostReport::class, mappedBy: 'Post', orphanRemoval: true)]
    private Collection $Report;

    /**
     * Liste des votes concernant ce message.
     * 
     * @var Collection<int, ForumPostVote>
     */
    #[ORM\OneToMany(targetEntity: ForumPostVote::class, mappedBy: 'Post', orphanRemoval: true)]
    private Collection $Vote;

    /**
     * Nombre de votes positifs pour ce message.
     * 
     * @var int|null
     */
    #[ORM\Column(nullable: true)]
    private ?int $VoteUp = null;

    public function __construct() {

        $this->Files = new ArrayCollection();
        $this->Report = new ArrayCollection();
        $this->Vote = new ArrayCollection();
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
     * Retourne la liste des fichiers joints au message.
     * 
     * @return Collection<Uuid, File>
     */
    public function getFiles(): Collection {

        return $this->Files;
    }

    /**
     * Ajoute un fichier à ce message.
     * 
     * @param File $file Fichier à ajouter.
     * @return static
     */
    public function addFile(File $file): static {

        if (!$this->Files->contains($file)) {

            $this->Files->add($file);
            $file->setPost($this);
        }

        return $this;
    }

    /**
     * Supprime un fichier de ce message.
     * 
     * @param File $file Fichier à supprimer.
     * @return static
     */
    public function removeFile(File $file): static {

        if ($this->Files->removeElement($file)) {

            // Annule la liaison avec le fichier
            if ($file->getPost() === $this) {

                $file->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Retourne le fil de discussion auquel ce message appartient.
     * 
     * @return ForumThread|null
     */
    public function getThread(): ?ForumThread {

        return $this->Thread;
    }

    /**
     * Définit le fil de discussion auquel ce message appartient.
     * 
     * @param ForumThread|null $Thread Fil de discussion.
     * @return static
     */
    public function setThread(?ForumThread $Thread): static {

        $this->Thread = $Thread;

        return $this;
    }

    /**
     * Retourne le contenu textuel du message.
     * 
     * @return string|null
     */
    public function getContent(): ?string {

        return $this->Content;
    }

    /**
     * Définit le contenu textuel du message.
     * 
     * @param string $Content Contenu du message.
     * @return static
     */
    public function setContent(string $Content): static {

        $this->Content = $Content;

        return $this;
    }

    /**
     * Retourne l'utilisateur ayant posté ce message.
     * 
     * @return User|null
     */
    public function getUser(): ?User {

        return $this->User;
    }

    /**
     * Définit l'utilisateur ayant posté ce message.
     * 
     * @param User|null $User Utilisateur.
     * @return static
     */
    public function setUser(?User $User): static {

        $this->User = $User;

        return $this;
    }

    /**
     * Retourne l'adresse IP de l'auteur du message.
     * 
     * @return string|null
     */
    public function getIp(): ?string {
        
        return $this->Ip;
    }

    /**
     * Définit l'adresse IP de l'auteur du message.
     * 
     * @param string $Ip Adresse IP.
     * @return static
     */
    public function setIp(string $Ip): static {

        $this->Ip = $Ip;

        return $this;
    }

    /**
     * Retourne la raison de la modération du message, si applicable.
     * 
     * @return string|null
     */
    public function getModerateReason(): ?string {

        return $this->ModerateReason;
    }

    /**
     * Définit la raison de la modération du message.
     * 
     * @param string|null $ModerateReason Raison de la modération.
     * @return static
     */
    public function setModerateReason(?string $ModerateReason): static {

        $this->ModerateReason = $ModerateReason;

        return $this;
    }

    /**
     * Retourne la liste des rapports concernant ce message.
     * 
     * @return Collection<int, ForumPostReport>
     */
    public function getReport(): Collection {

        return $this->Report;
    }

    /**
     * Ajoute un rapport concernant ce message.
     * 
     * @param ForumPostReport $report Rapport à ajouter.
     * @return static
     */
    public function addReport(ForumPostReport $report): static {

        if (!$this->Report->contains($report)) {

            $this->Report->add($report);
            $report->setPost($this);
        }

        return $this;
    }

    /**
     * Supprime un rapport concernant ce message.
     * 
     * @param ForumPostReport $report Rapport à supprimer.
     * @return static
     */
    public function removeReport(ForumPostReport $report): static {

        if ($this->Report->removeElement($report)) {

            // Annule la liaison avec le rapport
            if ($report->getPost() === $this) {

                $report->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Retourne la liste des votes concernant ce message.
     * 
     * @return Collection<int, ForumPostVote>
     */
    public function getVote(): Collection {

        return $this->Vote;
    }

    /**
     * Ajoute un vote concernant ce message.
     * 
     * @param ForumPostVote $vote Vote à ajouter.
     * @return static
     */
    public function addVote(ForumPostVote $vote): static {

        if (!$this->Vote->contains($vote)) {

            $this->Vote->add($vote);
            $vote->setPost($this);
        }

        return $this;
    }

    /**
     * Supprime un vote concernant ce message.
     * 
     * @param ForumPostVote $vote Vote à supprimer.
     * @return static
     */
    public function removeVote(ForumPostVote $vote): static {

        if ($this->Vote->removeElement($vote)) {

            // Annule la liaison avec le vote
            if ($vote->getPost() === $this) {

                $vote->setPost(null);
            }
        }

        return $this;
    }

    /**
     * Retourne le nombre de votes positifs pour ce message.
     * 
     * @return int|null
     */
    public function getVoteUp(): ?int {

        return $this->VoteUp;
    }

    /**
     * Définit le nombre de votes positifs pour ce message.
     * 
     * @param int|null $VoteUp Nombre de votes positifs.
     * @return static
     */
    public function setVoteUp(?int $VoteUp): static {
        
        $this->VoteUp = $VoteUp;

        return $this;
    }
}
