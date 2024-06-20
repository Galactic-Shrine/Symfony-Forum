<?php

namespace App\Entity;

use App\Entity\ForumThread;
use App\Entity\UserStatus;
use App\Enum\AvatarType;
use App\Enum\AvatarStyle;
use App\Enum\UserStatus as UserStatusEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['Email'], message: 'Cet e-mail existe déjà au sein de l\'application.')]
#[UniqueEntity(fields: ['UserName'], message: 'Ce nom d\'utilisateur existe déjà au sein de l\'application.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $Id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $UserName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Pseudo = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $FirstName = [];

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $LastName = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $Email = null;

    #[ORM\Column(nullable: true)]
    private ?array $Mail = null;

    #[ORM\Column(type: 'json')]
    private array $Roles = ['ROLE_USER'];

    // ***************************************
    // Section Status
    // ***************************************
    #[ORM\OneToOne(mappedBy: 'User', cascade: ['persist', 'remove'])]
    private UserStatus $Status;
    // ***************************************
    // End Section Status
    // ***************************************

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $Password = null;

    private ?string $plainPassword = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $Birthday = null;

    #[ORM\Column(nullable: true)]
    private ?array $Url = null;

    // ***************************************
    // Section Forum
    // ***************************************
    #[ORM\Column(length: 455, nullable: true)]
    private ?string $Signature = null;
    // ***************************************
    // End Section Forum
    // ***************************************

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $Picture = [
        "Type" => AvatarType::GrAvatar,
        "Style" => AvatarStyle::Square,
        "File" => null
    ];

    // ***************************************
    // Section Forum
    // ***************************************
    #[ORM\OneToOne(mappedBy: 'Author', cascade: ['persist', 'remove'])]
    private ?ForumThread $ForumThread = null;
    // ***************************************
    // End Section Forum
    // ***************************************

    // ***************************************
    // Section Linked Account Id
    // ***************************************
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $FacebookId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $GithubId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $GoogleId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $TwitchId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $X_Id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $YoutubeId = null;
    // ***************************************
    // End Section Linked Account Id
    // ***************************************

    #[ORM\Column(type: 'boolean')]
    private $IsVerified = false;

    #[ORM\Column(type: 'boolean')]
    private $IsEnabled = true;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $UpdateAt;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    private \DateTimeImmutable $CreateAt;

    // ***************************************
    // Section Messenger
    // ***************************************
    /**
     * @var Collection<Uuid, Messenger>
     */
    #[ORM\OneToMany(targetEntity: Messenger::class, mappedBy: 'Sender', orphanRemoval: true)]
    private Collection $MessegerSent;

    /**
     * @var Collection<Uuid, Messenger>
     */
    #[ORM\OneToMany(targetEntity: Messenger::class, mappedBy: 'Recipient', orphanRemoval: true)]
    private Collection $MessegerReceived;
    // ***************************************
    // End Section Messenger
    // ***************************************

    public function __construct() {
    
        $this->CreateAt = new \DateTimeImmutable;
        $this->MessegerSent = new ArrayCollection();
        $this->MessegerReceived = new ArrayCollection();
        $this->Picture = [
            "Type" => AvatarType::GrAvatar,
            "Style" => AvatarStyle::Square,
            "File" => null
        ];
    }

    public function getId(): ?Uuid {
    
        return $this->Id;
    }

    public function getUserName(): ?string {
    
        return $this->UserName;
    }

    public function setUserName(string $UserName): static {
    
        $this->UserName = $UserName;
    
        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->Pseudo;
    }

    public function setPseudo(?string $Pseudo): static
    {
        $this->Pseudo = $Pseudo;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $LastName): static
    {
        $this->LastName = $LastName;

        return $this;
    }

    public function getFirstName(): array {

        return array_unique($this->FirstName);
    }

    public function setFirstName(array $FirstName): static
    {
        $this->FirstName = $FirstName;

        return $this;
    }

    public function getEmail(): ?string {
    
        return $this->Email;
    }

    public function setEmail(string $Email): static {
    
        $this->Email = $Email;
        
        return $this;
    }

    public function getMail(): ?array
    {
        return $this->Mail;
    }

    public function setMail(?array $Mail): static
    {
        $this->Mail = $Mail;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array {
    
        $roles = $this->Roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        
        return array_unique($roles);
    }

    public function setRoles(array $Roles): static {
    
        $this->Roles = $Roles;
        
        return $this;
    }

    // ***************************************
    // Section Status
    // ***************************************
    public function getStatus(): ?UserStatus {

        return $this->Status;
    }

    public function setStatus(UserStatus $Status): self {

        if ($Status->getUser() !== $this) {

            $Status->setUser($this);
        }

        $this->Status = $Status;

        return $this;
    }

    public function getStatusEnum(): ?UserStatusEnum {

        return $this->Status ? $this->Status->getStatus() : null;
    }

    public function setStatusEnum(UserStatusEnum $statusEnum): self {

        if ($this->Status) {

            $this->Status->setStatus($statusEnum);
        } 
        else {
            
            $userStatus = new UserStatus();
            $userStatus->setUser($this);
            $userStatus->setStatus($statusEnum);
            $this->Status = $userStatus;
        }

        return $this;
    }
    // ***************************************
    // End Section Status
    // ***************************************

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string {
    
        return $this->Password;
    }

    public function setPassword(string $Password): static {
    
        $this->Password = $Password;
        
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void {
    
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->Birthday;
    }

    public function setBirthday(?\DateTimeImmutable $Birthday): static
    {
        $this->Birthday = $Birthday;

        return $this;
    }

    public function getUrl(): ?array
    {
        return $this->Url;
    }

    public function setUrl(?array $Url): static
    {
        $this->Url = $Url;

        return $this;
    }

    // ***************************************
    // Section Forum
    // ***************************************
    public function getSignature(): ?string
    {
        return $this->Signature;
    }

    public function setSignature(?string $Signature): static
    {
        $this->Signature = $Signature;

        return $this;
    }
    // ***************************************
    // End Section Forum
    // ***************************************

    public function getPicture(): ?array
    {
        return $this->Picture;
    }

    public function setPicture(?array $Picture): static
    {
        $this->Picture = $Picture;

        return $this;
    }

    // ***************************************
    // Section Forum
    // ***************************************
    public function getForumThread(): ?ForumThread
    {
        return $this->ForumThread;
    }

    public function setForumThread(ForumThread $ForumThread): static
    {
        // set the owning side of the relation if necessary
        if ($ForumThread->getAuthor() !== $this) {
            $ForumThread->setAuthor($this);
        }

        $this->ForumThread = $ForumThread;

        return $this;
    }
    // ***************************************
    // End Section Forum
    // ***************************************

    // ***************************************
    // Section Linked Account Id
    // ***************************************
    public function getFacebookId(): ?string {
    
        return $this->FacebookId;
    }

    public function setFacebookId(string $FacebookId): static {
    
        $this->FacebookId = $FacebookId;
        
        return $this;
    }

    public function getGithubId(): ?string {
    
        return $this->GithubId;
    }

    public function setGithubId(string $GithubId): static {

        $this->GithubId = $GithubId;

        return $this;
    }

    public function getGoogleId(): ?string {
    
        return $this->GoogleId;
    }

    public function setGoogleId(string $GoogleId): static {
    
        $this->GoogleId = $GoogleId;
        
        return $this;
    }

    public function getTwitchId(): ?string {
    
        return $this->TwitchId;
    }

    public function setTwitchId(string $TwitchId): static {
    
        $this->TwitchId = $TwitchId;
        
        return $this;
    }

    public function getX_Id(): ?string {
    
        return $this->X_Id;
    }

    public function setX_Id(string $X_Id): static {
    
        $this->X_Id = $X_Id;
        
        return $this;
    }

    public function getYoutubeId(): ?string {
    
        return $this->YoutubeId;
    }

    public function setYoutubeId(string $YoutubeId): static {
    
        $this->YoutubeId = $YoutubeId;
        
        return $this;
    }
    // ***************************************
    // End Section Linked Account Id
    // ***************************************

    public function getUpdateAt(): ?\DateTimeImmutable {

        return $this->UpdateAt;
    }
    
    public function setUpdateAt(?\DateTimeImmutable $UpdateAt): self {
    
        $this->UpdateAt = $UpdateAt;
    
        return $this;
    }

    public function getCreateAt(): \DateTimeImmutable {
    
        return $this->CreateAt;
    }

    public function setCreateAt(\DateTimeImmutable $CreateAt): self {
    
        $this->CreateAt = $CreateAt;
        
        return $this;
    }

    public function IsVerified(): bool {
    
        return $this->IsVerified;
    }

    public function setIsVerified(bool $IsVerified): static {

        $this->IsVerified = $IsVerified;
        
        return $this;
    }

    public function isEnabled(): ?bool {

        return $this->IsEnabled;
    }

    public function setIsEnabled(bool $IsEnabled): static {

        $this->IsEnabled = $IsEnabled;
        
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string {
    
        return (string) $this->Email;
    }

    // ***************************************
    // Section Messenger
    // ***************************************
    /**
     * @return Collection<Uuid, Messenger>
     */
    public function getMessegerSent(): Collection
    {
        return $this->MessegerSent;
    }

    public function addMessegerSent(Messenger $messegerSent): static
    {
        if (!$this->MessegerSent->contains($messegerSent)) {
            $this->MessegerSent->add($messegerSent);
            $messegerSent->setSender($this);
        }

        return $this;
    }

    public function removeMessegerSent(Messenger $messegerSent): static
    {
        if ($this->MessegerSent->removeElement($messegerSent)) {
            // set the owning side to null (unless already changed)
            if ($messegerSent->getSender() === $this) {
                $messegerSent->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<Uuid, Messenger>
     */
    public function getMessegerReceived(): Collection
    {
        return $this->MessegerReceived;
    }

    public function addMessegerReceived(Messenger $messegerReceived): static
    {
        if (!$this->MessegerReceived->contains($messegerReceived)) {
            $this->MessegerReceived->add($messegerReceived);
            $messegerReceived->setRecipient($this);
        }

        return $this;
    }

    public function removeMessegerReceived(Messenger $messegerReceived): static
    {
        if ($this->MessegerReceived->removeElement($messegerReceived)) {
            // set the owning side to null (unless already changed)
            if ($messegerReceived->getRecipient() === $this) {
                $messegerReceived->setRecipient(null);
            }
        }

        return $this;
    }
    // ***************************************
    // End Section Messenger
    // ***************************************
}
