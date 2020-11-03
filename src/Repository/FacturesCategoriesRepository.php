<?php

namespace App\Repository;

use App\Entity\FacturesCategories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FacturesCategories|null find($id, $lockMode = null, $lockVersion = null)
 * @method FacturesCategories|null findOneBy(array $criteria, array $orderBy = null)
 * @method FacturesCategories[]    findAll()
 * @method FacturesCategories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturesCategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FacturesCategories::class);
    }

    // /**
    //  * @return FacturesCategories[] Returns an array of FacturesCategories objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FacturesCategories
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
