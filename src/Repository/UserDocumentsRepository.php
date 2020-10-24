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

    public function findFirstNonFilledDoc($id, $user)
    {
        return $this->createQueryBuilder("userDoc")
            ->andWhere("userDoc.document = :document")
            ->andWhere("userDoc.user = :user")
            ->andWhere("userDoc.generated_pdf is null")
            ->setParameter('document', $id)
            ->setParameter('user', $user)
            ->setMaxResults(1)
            ->getQuery()
            ->execute();
    }

    public function addPdfToRow($id, $pdfPath, $timestamp)
    {
        $formattedDate = $timestamp->format("YmdHis");

        return $this->createQueryBuilder('p')
            ->update($this->getClassName(), 'ud')
            ->where('ud.id = :id')
            ->set('ud.generated_pdf', ':generated_pdf')
            ->set('ud.generated_at', ':datetime')
            ->setParameter('id', $id)
            ->setParameter('generated_pdf', $pdfPath.$formattedDate)
            ->setParameter('datetime', $timestamp)
            ->getQuery()
            ->execute();
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
