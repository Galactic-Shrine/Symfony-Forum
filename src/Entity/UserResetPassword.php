<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Entity;

use App\Repository\UserResetPasswordRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;

#[ORM\Entity(repositoryClass: UserResetPasswordRepository::class)]
class UserResetPassword implements ResetPasswordRequestInterface {
    use ResetPasswordRequestTrait;

    /**
     * @var Uuid|null L'identifiant unique de la demande de réinitialisation.
     */
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    /**
     * @var User|null L'utilisateur pour lequel la réinitialisation du mot de passe a été demandée.
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Initialise une nouvelle demande de réinitialisation du mot de passe.
     *
     * @param User $user L'utilisateur pour lequel la réinitialisation est demandée.
     * @param \DateTimeInterface $expiresAt La date et l'heure d'expiration de la demande.
     * @param string $selector Le sélecteur du jeton de réinitialisation.
     * @param string $hashedToken Le jeton de réinitialisation haché.
     */
    public function __construct(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken) {
        
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    /**
     * Retourne l'identifiant de la demande de réinitialisation.
     *
     * @return Uuid|null L'identifiant de la demande ou null si non défini.
     */
    public function getId(): ?Uuid {

        return $this->id;
    }

    /**
     * Retourne l'utilisateur associé à cette demande de réinitialisation.
     *
     * @return User L'utilisateur pour lequel la réinitialisation est demandée.
     */
    public function getUser(): object {

        return $this->user;
    }
}
