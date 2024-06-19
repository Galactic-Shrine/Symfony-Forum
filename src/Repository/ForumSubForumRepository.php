<?php

namespace App\Repository;

use App\Entity\ForumSubForum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumSubForum>
 *
 * @method ForumSubForum|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumSubForum|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumSubForum[]    findAll()
 * @method ForumSubForum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumSubForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumSubForum::class);
    }

//    /**
//     * @return ForumSubForum[] Returns an array of ForumSubForum objects
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

//    public function findOneBySomeField($value): ?ForumSubForum
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
