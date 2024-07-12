<?php

namespace App\Service;

use App\Entity\Config;
use Doctrine\ORM\EntityManagerInterface;

class ConfigService {
    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getConfigValue(string $Name): ?string {

        $Config = $this->em->getRepository(Config::class)->findOneBy(['Name' => $Name]);

        return $Config ? $Config->getValue() : null;
    }
}