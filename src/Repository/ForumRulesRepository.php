<?php

namespace App\Repository;

use App\Entity\ForumRules;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ForumRules>
 *
 * @method ForumRules|null find($id, $lockMode = null, $lockVersion = null)
 * @method ForumRules|null findOneBy(array $criteria, array $orderBy = null)
 * @method ForumRules[]    findAll()
 * @method ForumRules[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ForumRulesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ForumRules::class);
    }

//    /**
//     * @return ForumRules[] Returns an array of ForumRules objects
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

//    public function findOneBySomeField($value): ?ForumRules
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
