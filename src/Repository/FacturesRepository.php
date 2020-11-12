<?php

namespace App\Repository;

use App\Entity\Factures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Factures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Factures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Factures[]    findAll()
 * @method Factures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Factures::class);
    }

    public function searchByName($name, $userid)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.clientName LIKE :clientname')
            ->setParameter('user', $userid)
            ->setParameter('clientname', '%'.$name.'%')
            ->getQuery()
            ->getResult();
    }

    public function searchByDate($user, $date) {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.date >= :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    public function searchBetweenDates($user,\DateTimeInterface $date1,\DateTimeInterface $date2) {

        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.date > :date1')
            ->andWhere('o.date < :date2')
            ->setParameter('user', $user)
            ->setParameter('date1', $date1->format("Y-m-d"))
            ->setParameter('date2', $date2->format("Y-m-d"))
            ->getQuery()
            ->getResult();
    }

    public function searchByNameBetweenDates($user, $name, \DateTimeInterface $date1, \DateTimeInterface $date2)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.clientName LIKE :clientname')
            ->andWhere('o.date >= :date1')
            ->andWhere('o.date <= :date2')
            ->setParameter('user', $user)
            ->setParameter('clientname', '%'.$name.'%')
            ->setParameter('date1', $date1->format("Y-m-d"))
            ->setParameter('date2', $date2->format("Y-m-d"))
            ->getQuery()
            ->getResult();
    }

    public function searchByNameAndDate($user, $name, $date)
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.clientName LIKE :clientname')
            ->andWhere('o.date >= :date')
            ->setParameter('user', $user)
            ->setParameter('clientname', '%'.$name.'%')
            ->setParameter('date', $date->format("Y-m-d"))
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Factures[] Returns an array of Factures objects
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
    public function findOneBySomeField($value): ?Factures
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
