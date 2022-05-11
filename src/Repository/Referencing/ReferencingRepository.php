<?php

namespace App\Repository\Referencing;

use App\Entity\Referencing\Referencing;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Referencing|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referencing|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referencing|null findOneByUrl(string $url)
 * @method Referencing[]    findAll()
 * @method Referencing[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferencingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referencing::class);
    }

    // /**
    //  * @return Referencing[] Returns an array of Referencing objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Referencing
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
