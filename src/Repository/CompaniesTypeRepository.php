<?php

namespace App\Repository;

use App\Entity\CompaniesType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompaniesType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompaniesType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompaniesType[]    findAll()
 * @method CompaniesType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompaniesTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompaniesType::class);
    }

    // /**
    //  * @return CompaniesType[] Returns an array of CompaniesType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompaniesType
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
