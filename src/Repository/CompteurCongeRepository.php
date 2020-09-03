<?php

namespace App\Repository;

use App\Entity\CompteurConge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompteurConge|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompteurConge|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompteurConge[]    findAll()
 * @method CompteurConge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompteurCongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompteurConge::class);
    }

    // /**
    //  * @return CompteurConge[] Returns an array of CompteurConge objects
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
    public function findOneBySomeField($value): ?CompteurConge
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
