<?php

namespace App\Repository;

use App\Entity\ForumForum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumForum>
 *
 * @method ForumForum|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumForum|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumForum[]    findAll()
 * @method ForumForum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumForumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumForum::class);
    }

//    /**
//     * @return ForumForum[] Returns an array of ForumForum objects
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

//    public function findOneBySomeField($value): ?ForumForum
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
