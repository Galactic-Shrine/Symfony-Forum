<?php

/**
 * @copyright © ⋞Galactic-Shrine⋟ 2020-2024, Tous droits réservés.
 *
 * @author ⋞Galactic-Shrine⋟ <support@galactic-shrine.com>
 * @author James Ramon @GsKizuna <kizuna@galactic-shrine.com>
 * Ce fichier fait partie du projet Symfony-Forum développé par ⋞Galactic-Shrine⋟ et sa communauté.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPresence;
use App\Enum\UserStatus;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service pour gérer la présence des utilisateurs.
 * 
 * Cette classe fournit des méthodes pour mettre à jour le statut de présence d'un utilisateur.
 * Elle utilise le gestionnaire d'entités Doctrine pour interagir avec la base de données et
 * mettre à jour ou créer des entrées de présence utilisateur en fonction des besoins.
 */
class UserPresenceService {

    /**
     * Le gestionnaire d'entités Doctrine pour interagir avec la base de données.
     * 
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * Constructeur pour injecter le service EntityManagerInterface.
     * 
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités Doctrine
     */
    public function __construct(EntityManagerInterface $entityManager) {

        $this->entityManager = $entityManager;
    }

    /**
     * Met à jour le statut de présence d'un utilisateur.
     * 
     * Cette méthode vérifie si l'utilisateur a déjà une entrée de présence. Si ce n'est pas le cas,
     * une nouvelle entrée de présence est créée et associée à l'utilisateur. Ensuite, le statut de présence
     * de l'utilisateur est mis à jour et les changements sont sauvegardés dans la base de données.
     * 
     * @param User $User L'utilisateur dont le statut de présence doit être mis à jour
     * @param UserStatus $Status Le nouveau statut de présence de l'utilisateur
     * 
     * @return void
     */
    public function updateStatus(User $User, UserStatus $Status): void {

        // Obtenir la présence actuelle de l'utilisateur
        $Presence = $User->getPresence();

        // Si l'utilisateur n'a pas de présence associée, en créer une nouvelle
        if (!$Presence) {
            
            $Presence = new UserPresence();
            $Presence->setUser(User: $User);
            $User->setPresence(Presence: $Presence);
            $this->entityManager->persist(object: $Presence);
        }

        // Mettre à jour le statut de présence
        $Presence->setStatus(Status: $Status);

        // Sauvegarder les modifications dans la base de données
        $this->entityManager->flush();
    }
}
