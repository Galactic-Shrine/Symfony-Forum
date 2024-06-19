<?php

namespace App\Repository;

use App\Entity\RequestResetPassword;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * @extends ServiceEntityRepository<RequestResetPassword>
 *
 * @method RequestResetPassword|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestResetPassword|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestResetPassword[]    findAll()
 * @method RequestResetPassword[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestResetPasswordRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestResetPassword::class);
    }

    public function createResetPasswordRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): ResetPasswordRequestInterface
    {
        return new RequestResetPassword($user, $expiresAt, $selector, $hashedToken);
    }
}
