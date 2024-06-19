<?php

namespace App\Repository;

use App\Entity\ForumSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumSubscription>
 *
 * @method ForumSubscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumSubscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumSubscription[]    findAll()
 * @method ForumSubscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumSubscription::class);
    }

    //    /**
    //     * @return ForumSubscription[] Returns an array of ForumSubscription objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ForumSubscription
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
