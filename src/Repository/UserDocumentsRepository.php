<?php

namespace App\Repository;

use App\Entity\UserDocuments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserDocuments|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserDocuments|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserDocuments[]    findAll()
 * @method UserDocuments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserDocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserDocuments::class);
    }

    public function findFirstNonFilledDoc()
    {
        return $this->createQueryBuilder("userDoc");
    }

    // /**
    //  * @return UserDocuments[] Returns an array of UserDocuments objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserDocuments
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
