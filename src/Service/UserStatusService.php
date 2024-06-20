<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserStatus;
use App\Enum\UserStatus as UserStatusEnum;
use Doctrine\ORM\EntityManagerInterface;

class UserStatusService {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {

        $this->entityManager = $entityManager;
    }

    public function updateStatus(User $user, UserStatusEnum $status): void {

        $user->setStatusEnum($status);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}