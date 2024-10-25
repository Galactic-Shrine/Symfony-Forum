<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Enum\AvatarType;
use App\Enum\AvatarStyle;
use App\Entity\ForumThread;
use App\Enum\UserStatus;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
//use Ramsey\Uuid\Doctrine\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('Email', 'C\'est e-mail existe déjà au sein de l\'application.')]
#[UniqueEntity('UserName', 'Ce nom d\'utilisateur existe déjà au sein de l\'application.')]
/**
 * Représente un utilisateur de l'application.
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    #region User Classic
    /**
     * Identifiant unique de l'utilisateur.
     * 
     * @var Uuid|null
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    /**
     * Nom d'utilisateur unique.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 180, unique: true)]
    private ?string $UserName = null;

    /**
     * Pseudo de l'utilisateur, si applicable.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Pseudo = null;

    /**
     * Prénom de l'utilisateur, stocké sous forme de tableau JSON.
     * 
     * @var array|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $FirstName = [];

    /**
     * Nom de famille de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LastName = null;

    /**
     * Adresse e-mail unique de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 180, unique: true)]
    private ?string $Email = null;

    /**
     * Autres adresses e-mail associées à l'utilisateur.
     * 
     * @var array|null
     */
    #[ORM\Column(nullable: true)]
    private ?array $Mail = null;

    /**
     * Adresse de l'utilisateur.
     * 
     * @var array|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $Address = null;

    /**
     * Numéro de téléphone de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $PhoneNumber = null;

    /**
     * Rôles attribués à l'utilisateur.
     * 
     * @var array
     */
    #[ORM\Column(type: 'json')]
    private array $Roles = ['ROLE_USER'];
    #endregion

    #region User Presence
    /**
     * Présence de l'utilisateur dans le système.
     * 
     * @var UserPresence
     */
    #[ORM\OneToOne(targetEntity: UserPresence::class, mappedBy: 'User', cascade: ['persist', 'remove'])]
    private UserPresence $Presence;
    #endregion

    #region User Classic
    /**
     * Mot de passe haché de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column]
    private ?string $Password = null;

    /**
     * Mot de passe en clair avant le hachage.
     * 
     * @var string|null
     */
    private ?string $plainPassword = null;

    /**
     * Code local (par exemple, 'fr' pour la France).
     * Ce champ peut être null.
     *
     * @var string|null
     */
    #[ORM\Column(length: 2, nullable: true)]
    private ?string $Local = null;

    /**
     * Date de naissance de l'utilisateur.
     * 
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $Birthday = null;

    /**
     * URL associées à l'utilisateur.
     * 
     * @var array|null
     */
    #[ORM\Column(nullable: true)]
    private ?array $Url = null;
    #endregion

    #region Forum
    /**
     * Signature de l'utilisateur pour les forums.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 455, nullable: true)]
    private ?string $Signature = null;
    #endregion

    #region User Classic
    /**
     * Informations sur l'avatar de l'utilisateur.
     * 
     * @var array
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $Picture = [
        "Type" => AvatarType::GrAvatar,
        "Style" => AvatarStyle::Square,
        "File" => null
    ];
    #endregion

    #region Forum
    /**
     * Fil de discussion créé par l'utilisateur.
     * 
     * @var ForumThread|null
     */
    #[ORM\OneToOne(mappedBy: 'Author', cascade: ['persist', 'remove'])]
    private ?ForumThread $ForumThread = null;
    #endregion

    #region Linked Account Id
    /**
     * Identifiant Facebook de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FacebookId = null;

    /**
     * Identifiant Github de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $GithubId = null;

    /**
     * Identifiant Google de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $GoogleId = null;

    /**
     * Identifiant Twitch de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TwitchId = null;

    /**
     * Identifiant X de l'utilisateur (anciennement Twitter).
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $X_Id = null;

    /**
     * Identifiant YouTube de l'utilisateur.
     * 
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $YoutubeId = null;
    #endregion

    #region User Classic
    /**
     * Indique si l'utilisateur a vérifié son adresse e-mail.
     * 
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private $IsVerified = false;

    /**
     * Indique si le compte de l'utilisateur est activé.
     * 
     * @var bool
     */
    #[ORM\Column(type: 'boolean')]
    private $IsEnabled = true;

    /**
     * Date et heure de la dernière mise à jour du profil.
     * 
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $UpdateAt;

    /**
     * Date et heure de la création du compte.
     * 
     * @var \DateTimeImmutable
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private \DateTimeImmutable $CreateAt;
    #endregion

    #region Messaging
    /**
     * Messages envoyés par l'utilisateur.
     * 
     * @var Collection<Uuid, MessagingMessages>
     */
    #[ORM\OneToMany(targetEntity: MessagingMessages::class, mappedBy: 'Sender', orphanRemoval: true)]
    private Collection $MessagingSent;

    /**
     * Messages reçus par l'utilisateur.
     * 
     * @var Collection<Uuid, MessagingMessages>
     */
    #[ORM\OneToMany(targetEntity: MessagingMessages::class, mappedBy: 'Recipient', orphanRemoval: true)]
    private Collection $MessagingReceived;

    /**
     * @var Collection<int, MessagingFolder>
     */
    #[ORM\ManyToMany(targetEntity: MessagingFolder::class, mappedBy: 'User')]
    private Collection $MessagingFolders;
    #endregion

    public function __construct() {

        $this->Presence = new UserPresence();
        $this->Presence->setUser($this)->setStatus(UserStatus::ONLINE);
        $this->Picture = [
            "Type" => AvatarType::GrAvatar,
            "Style" => AvatarStyle::Square,
            "File" => null
        ];
        $this->MessagingSent = new ArrayCollection();
        $this->MessagingReceived = new ArrayCollection();
        $this->CreateAt = new \DateTimeImmutable;
        $this->MessagingFolders = new ArrayCollection();
    }

    #region User Classic
    /**
     * Retourne l'identifiant unique de l'utilisateur.
     * 
     * @return Uuid|null
     */
    public function getId(): ?Uuid {

        return $this->Id;
    }

    /**
     * Retourne le nom d'utilisateur.
     * 
     * @return string|null
     */
    public function getUserName(): ?string {

        return $this->UserName;
    }

    /**
     * Définit le nom d'utilisateur.
     * 
     * @param string $UserName Nom d'utilisateur.
     * @return static
     */
    public function setUserName(string $UserName): static {

        $this->UserName = $UserName;

        return $this;
    }

    /**
     * Retourne le pseudo de l'utilisateur.
     * 
     * @return string|null
     */
    public function getPseudo(): ?string {

        return $this->Pseudo;
    }

    /**
     * Définit le pseudo de l'utilisateur.
     * 
     * @param string|null $Pseudo Pseudo.
     * @return static
     */
    public function setPseudo(?string $Pseudo): static {

        $this->Pseudo = $Pseudo;

        return $this;
    }

    /**
     * Retourne le nom de famille de l'utilisateur.
     * 
     * @return string|null
     */
    public function getLastName(): ?string {

        return $this->LastName;
    }

    /**
     * Définit le nom de famille de l'utilisateur.
     * 
     * @param string|null $LastName Nom de famille.
     * @return static
     */
    public function setLastName(?string $LastName): static {

        $this->LastName = $LastName;

        return $this;
    }

    /**
     * Retourne le prénom de l'utilisateur sous forme de tableau.
     * 
     * @return array
     */
    public function getFirstName(): ?array {

        return $this->FirstName ? array_unique($this->FirstName) : [];
    }

    /**
     * Définit le prénom de l'utilisateur.
     * 
     * @param array $FirstName Tableau de prénoms.
     * @return static
     */
    public function setFirstName(?array $FirstName): static {

        $this->FirstName = $FirstName;

        return $this;
    }

    /**
     * Retourne l'adresse e-mail de l'utilisateur.
     * 
     * @return string|null
     */
    public function getEmail(): ?string {

        return $this->Email;
    }

    /**
     * Définit l'adresse e-mail de l'utilisateur.
     * 
     * @param string $Email Adresse e-mail.
     * @return static
     */
    public function setEmail(string $Email): static {

        $this->Email = $Email;

        return $this;
    }

    /**
     * Retourne les autres adresses e-mail associées à l'utilisateur.
     * 
     * @return array|null
     */
    public function getMail(): ?array {

        return $this->Mail;
    }

    /**
     * Définit les autres adresses e-mail associées à l'utilisateur.
     * 
     * @param array|null $Mail Tableau d'adresses e-mail.
     * @return static
     */
    public function setMail(?array $Mail): static {

        $this->Mail = $Mail;

        return $this;
    }

    /**
     * Retourne l'adresse de l'utilisateur.
     * 
     * @return array|null
     */
    public function getAddress(): ?array {

        return $this->Address;
    }

    /**
     * Définit l'adresse de l'utilisateur.
     * 
     * @param array|null $Address
     * @return static
     */
    public function setAddress(?array $Address): static {

        $this->Address = $Address;

        return $this;
    }

    /**
     * Retourne le numéro de téléphone de l'utilisateur.
     * 
     * @return string|null
     */
    public function getPhoneNumber(): ?string {

        return $this->PhoneNumber;
    }

    /**
     * Définit le numéro de téléphone de l'utilisateur.
     * 
     * @param string|null $PhoneNumber
     * @return static
     */
    public function setPhoneNumber(?string $PhoneNumber): static {

        $this->PhoneNumber = $PhoneNumber;

        return $this;
    }

    /**
     * Retourne les rôles attribués à l'utilisateur.
     * 
     * @return array
     */
    public function getRoles(): array {

        $roles = $this->Roles;
        // garantir que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     * 
     * @param array $Roles Tableau de rôles.
     * @return static
     */
    public function setRoles(array $Roles): static {

        $this->Roles = $Roles;

        return $this;
    }
    #endregion

    #region User Presence
    /**
     * Retourne la présence de l'utilisateur.
     * 
     * @return UserPresence|null
     */
    public function getPresence(): ?UserPresence {

        return $this->Presence;
    }

    /**
     * Définit la présence de l'utilisateur.
     * 
     * @param UserPresence $Presence Objet de présence.
     * @return static
     */
    public function setPresence(UserPresence $Presence): self {

        if ($Presence->getUser() !== $this) {

            $Presence->setUser($this);
        }

        $this->Presence = $Presence;
        return $this;
    }
    #endregion

    #region User Classic
    /**
     * Retourne le mot de passe de l'utilisateur.
     * 
     * @return string
     */
    public function getPassword(): string {

        return $this->Password;
    }

    /**
     * Définit le mot de passe de l'utilisateur.
     * 
     * @param string $Password Mot de passe.
     * @return static
     */
    public function setPassword(string $Password): static {

        $this->Password = $Password;

        return $this;
    }

    /**
     * Récupère le code local.
     *
     * @return string|null Le code local ou null si non défini.
     */
    public function getLocal(): ?string {
        
        return $this->Local;
    }

    /**
     * Définit le code local.
     *
     * @param string|null $local Le code local à définir.
     * @return self Retourne l'instance courante pour la fluidité.
     */
    public function setLocal(?string $Local): self {

        $this->Local = $Local;

        return $this;
    }

    /**
     * Efface les informations sensibles, comme le mot de passe en clair.
     */
    public function eraseCredentials(): void {

        $this->plainPassword = null;
    }

    /**
     * Retourne la date de naissance de l'utilisateur.
     * 
     * @return \DateTimeImmutable|null
     */
    public function getBirthday(): ?\DateTimeImmutable {

        return $this->Birthday;
    }

    /**
     * Définit la date de naissance de l'utilisateur.
     * 
     * @param \DateTimeImmutable|null $Birthday Date de naissance.
     * @return static
     */
    public function setBirthday(?\DateTimeImmutable $Birthday): static {

        $this->Birthday = $Birthday;

        return $this;
    }

    /**
     * Retourne les URL associées à l'utilisateur.
     * 
     * @return array|null
     */
    public function getUrl(): ?array {

        return $this->Url;
    }

    /**
     * Définit les URL associées à l'utilisateur.
     * 
     * @param array|null $Url Tableau d'URL.
     * @return static
     */
    public function setUrl(?array $Url): static {

        $this->Url = $Url;

        return $this;
    }
    #endregion

    #region Forum
    /**
     * Retourne la signature de l'utilisateur pour les forums.
     * 
     * @return string|null
     */
    public function getSignature(): ?string {

        return $this->Signature;
    }

    /**
     * Définit la signature de l'utilisateur pour les forums.
     * 
     * @param string|null $Signature Signature.
     * @return static
     */
    public function setSignature(?string $Signature): static {

        $this->Signature = $Signature;

        return $this;
    }
    #endregion

    #region User Classic
    /**
     * Retourne les informations sur l'avatar de l'utilisateur.
     * 
     * @return array|null
     */
    public function getPicture(): ?array {

        return $this->Picture;
    }

    /**
     * Définit les informations sur l'avatar de l'utilisateur.
     * 
     * @param array|null $Picture Informations sur l'avatar.
     * @return static
     */
    public function setPicture(?array $Picture): static {

        $this->Picture = $Picture;

        return $this;
    }
    #endregion

    #region Forum
    /**
     * Retourne le fil de discussion créé par l'utilisateur.
     * 
     * @return ForumThread|null
     */
    public function getForumThread(): ?ForumThread {

        return $this->ForumThread;
    }

    /**
     * Définit le fil de discussion créé par l'utilisateur.
     * 
     * @param ForumThread $ForumThread Fil de discussion.
     * @return static
     */
    public function setForumThread(ForumThread $ForumThread): static {

        if ($ForumThread->getAuthor() !== $this) {

            $ForumThread->setAuthor($this);
        }

        $this->ForumThread = $ForumThread;

        return $this;
    }
    #endregion

    #region Linked Account Id
    /**
     * Retourne l'identifiant Facebook de l'utilisateur.
     * 
     * @return string|null
     */
    public function getFacebookId(): ?string {

        return $this->FacebookId;
    }

    /**
     * Définit l'identifiant Facebook de l'utilisateur.
     * 
     * @param string $FacebookId Identifiant Facebook.
     * @return static
     */
    public function setFacebookId(string $FacebookId): static {

        $this->FacebookId = $FacebookId;

        return $this;
    }

    /**
     * Retourne l'identifiant Github de l'utilisateur.
     * 
     * @return string|null
     */
    public function getGithubId(): ?string {

        return $this->GithubId;
    }

    /**
     * Définit l'identifiant Github de l'utilisateur.
     * 
     * @param string $GithubId Identifiant Github.
     * @return static
     */
    public function setGithubId(string $GithubId): static {
        
        $this->GithubId = $GithubId;

        return $this;
    }

    /**
     * Retourne l'identifiant Google de l'utilisateur.
     * 
     * @return string|null
     */
    public function getGoogleId(): ?string {

        return $this->GoogleId;
    }

    /**
     * Définit l'identifiant Google de l'utilisateur.
     * 
     * @param string $GoogleId Identifiant Google.
     * @return static
     */
    public function setGoogleId(string $GoogleId): static {

        $this->GoogleId = $GoogleId;

        return $this;
    }

    /**
     * Retourne l'identifiant Twitch de l'utilisateur.
     * 
     * @return string|null
     */
    public function getTwitchId(): ?string {

        return $this->TwitchId;
    }

    /**
     * Définit l'identifiant Twitch de l'utilisateur.
     * 
     * @param string $TwitchId Identifiant Twitch.
     * @return static
     */
    public function setTwitchId(string $TwitchId): static {

        $this->TwitchId = $TwitchId;

        return $this;
    }

    /**
     * Retourne l'identifiant X (anciennement Twitter) de l'utilisateur.
     * 
     * @return string|null
     */
    public function getX_Id(): ?string {

        return $this->X_Id;
    }

    /**
     * Définit l'identifiant X (anciennement Twitter) de l'utilisateur.
     * 
     * @param string $X_Id Identifiant X.
     * @return static
     */
    public function setX_Id(string $X_Id): static {

        $this->X_Id = $X_Id;

        return $this;
    }

    /**
     * Retourne l'identifiant Youtube de l'utilisateur.
     * 
     * @return string|null
     */
    public function getYoutubeId(): ?string {

        return $this->YoutubeId;
    }

    /**
     * Définit l'identifiant Youtube de l'utilisateur.
     * 
     * @param string $YoutubeId Identifiant Youtube.
     * @return static
     */
    public function setYoutubeId(string $YoutubeId): static {
        
        $this->YoutubeId = $YoutubeId;

        return $this;
    }
    #endregion

    #region User Classic
    /**
     * Retourne la date de dernière mise à jour des informations de l'utilisateur.
     * 
     * @return \DateTimeImmutable|null
     */
    public function getUpdateAt(): ?\DateTimeImmutable {

        return $this->UpdateAt;
    }

    /**
     * Définit la date de dernière mise à jour des informations de l'utilisateur.
     * 
     * @param \DateTimeImmutable|null $UpdateAt Date de mise à jour.
     * @return static
     */
    public function setUpdateAt(?\DateTimeImmutable $UpdateAt): self {

        $this->UpdateAt = $UpdateAt;

        return $this;
    }

    /**
     * Retourne la date de création de l'utilisateur.
     * 
     * @return \DateTimeImmutable
     */
    public function getCreateAt(): \DateTimeImmutable {

        return $this->CreateAt;
    }

    /**
     * Définit la date de création de l'utilisateur.
     * 
     * @param \DateTimeImmutable $CreateAt Date de création.
     * @return static
     */
    public function setCreateAt(\DateTimeImmutable $CreateAt): self {

        $this->CreateAt = $CreateAt;

        return $this;
    }

    /**
     * Indique si l'utilisateur est vérifié.
     * 
     * @return bool
     */
    public function IsVerified(): bool {

        return $this->IsVerified;
    }

    /**
     * Définit si l'utilisateur est vérifié.
     * 
     * @param bool $IsVerified État de vérification.
     * @return static
     */
    public function setIsVerified(bool $IsVerified): static {

        $this->IsVerified = $IsVerified;

        return $this;
    }

    /**
     * Indique si l'utilisateur est activé.
     * 
     * @return bool|null
     */
    public function isEnabled(): ?bool {

        return $this->IsEnabled;
    }

    /**
     * Définit si l'utilisateur est activé.
     * 
     * @param bool $IsEnabled État d'activation.
     * @return static
     */
    public function setIsEnabled(bool $IsEnabled): static {

        $this->IsEnabled = $IsEnabled;

        return $this;
    }

    /**
     * Retourne l'identifiant unique de l'utilisateur.
     * 
     * @return string
     */
    public function getUserIdentifier(): string {

        return (string) $this->Email;
    }
    #endregion

    #region Messaging Messages
    /**
     * Retourne les messages envoyés par l'utilisateur.
     * 
     * @return Collection<Uuid, MessagingMessages>
     */
    public function getMessagingSent(): Collection {

        return $this->MessagingSent;
    }

    /**
     * Ajoute un message envoyé par l'utilisateur.
     * 
     * @param MessagingMessages $MessagingSent Message envoyé.
     * @return static
     */
    public function addMessagingSent(MessagingMessages $MessagingSent): static {

        if (!$this->MessagingSent->contains($MessagingSent)) {

            $this->MessagingSent->add(element: $MessagingSent);
            $MessagingSent->setSender($this);
        }

        return $this;
    }

    /**
     * Supprime un message envoyé par l'utilisateur.
     * 
     * @param MessagingMessages $MessagingSent Message envoyé.
     * @return static
     */
    public function removeMessagingSent(MessagingMessages $MessagingSent): static {

        if ($this->MessagingSent->removeElement($MessagingSent)) {

            if ($MessagingSent->getSender() === $this) {

                $MessagingSent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * Retourne les messages reçus par l'utilisateur.
     * 
     * @return Collection<Uuid, MessagingMessages>
     */
    public function getMessagingReceived(): Collection {

        return $this->MessagingReceived;
    }

    /**
     * Ajoute un message reçu par l'utilisateur.
     * 
     * @param MessagingMessages $MessagingReceived Message reçu.
     * @return static
     */
    public function addMessagingReceived(MessagingMessages $MessagingReceived): static {

        if (!$this->MessagingReceived->contains($MessagingReceived)) {

            $this->MessagingReceived->add($MessagingReceived);
            $MessagingReceived->setRecipient($this);
        }

        return $this;
    }

    /**
     * Supprime un message reçu par l'utilisateur.
     * 
     * @param MessagingMessages $MessagingReceived Message reçu.
     * @return static
     */
    public function removeMessagingReceived(MessagingMessages $MessagingReceived): static {

        if ($this->MessagingReceived->removeElement($MessagingReceived)) {

            if ($MessagingReceived->getRecipient() === $this) {

                $MessagingReceived->setRecipient(null);
            }
        }

        return $this;
    }
    #endregion

    #region Messaging Folder
    /**
     * @return Collection<int, MessagingFolder>
     */
    public function getMessagingFolders(): Collection
    {
        return $this->MessagingFolders;
    }

    public function addMessagingFolder(MessagingFolder $messagingFolder): static
    {
        if (!$this->MessagingFolders->contains($messagingFolder)) {
            $this->MessagingFolders->add($messagingFolder);
            $messagingFolder->addUser($this);
        }

        return $this;
    }

    public function removeMessagingFolder(MessagingFolder $messagingFolder): static
    {
        if ($this->MessagingFolders->removeElement($messagingFolder)) {
            $messagingFolder->removeUser($this);
        }

        return $this;
    }
    #endregion
}