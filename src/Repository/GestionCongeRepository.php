<?php

namespace App\Repository;

use App\Entity\GestionConge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GestionConge|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestionConge|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestionConge[]    findAll()
 * @method GestionConge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestionCongeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GestionConge::class);
    }

    // /**
    //  * @return GestionConge[] Returns an array of GestionConge objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GestionConge
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
