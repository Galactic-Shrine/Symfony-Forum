<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class UserLocationService {
    
    private $entityManager;
    private $requestStack;
    private $cache;

    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack) {

        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->cache = new FilesystemAdapter(); // Ou utilisez un autre adaptateur de cache
    }

    public function updateLocation(User $user, string $controller, string $action): void {

        $cacheKey = 'user_location_' . $user->getId();
        $locationData = [
            'controller' => $controller,
            'action' => $action,
            'timestamp' => time(),
        ];

        // Stocker les données de localisation dans le cache
        $this->cache->get($cacheKey, function (ItemInterface $item) use ($locationData) {

            $item->set($locationData);
            $item->expiresAfter(3600); // 1 heure
            return $locationData;
        });
    }

    public function getLocation(User $user): array {

        $cacheKey = 'user_location_' . $user->getId();
        $locationData = $this->cache->getItem($cacheKey)->get();

        if ($locationData === null) {

            return [
                'controller' => null,
                'action' => null,
                'timestamp' => null,
            ];
        }

        return $locationData;
    }
}