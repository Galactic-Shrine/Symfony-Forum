<?php

namespace App\Repository;

use App\Entity\ForumPostVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumPostVote>
 *
 * @method ForumPostVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumPostVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumPostVote[]    findAll()
 * @method ForumPostVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumPostVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumPostVote::class);
    }

    //    /**
    //     * @return ForumPostVote[] Returns an array of ForumPostVote objects
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

    //    public function findOneBySomeField($value): ?ForumPostVote
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
