<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPresence;
use App\Enum\UserStatus;
use Doctrine\ORM\EntityManagerInterface;

class UserPresenceService {
    
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {

        $this->entityManager = $entityManager;
    }

    public function updateStatus(User $User, UserStatus $Status): void {

        $Presence = $User->getPresence();

        if (!$Presence) {

            $Presence = new UserPresence();
            $Presence->setUser($User);
            $User->setPresence($Presence);
            $this->entityManager->persist($Presence);
        }

        $Presence->setStatus($Status);
        $this->entityManager->flush();
    }
}
