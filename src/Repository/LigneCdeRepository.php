<?php

namespace App\Repository;

use App\Entity\LigneCde;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LigneCde|null find($id, $lockMode = null, $lockVersion = null)
 * @method LigneCde|null findOneBy(array $criteria, array $orderBy = null)
 * @method LigneCde[]    findAll()
 * @method LigneCde[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LigneCdeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LigneCde::class);
    }

    // /**
    //  * @return LigneCde[] Returns an array of LigneCde objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LigneCde
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
