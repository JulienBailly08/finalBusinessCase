<?php

namespace App\Repository;

use App\Entity\QuantityOnCommand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuantityOnCommand|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuantityOnCommand|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuantityOnCommand[]    findAll()
 * @method QuantityOnCommand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuantityOnCommandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuantityOnCommand::class);
    }

    // /**
    //  * @return QuantityOnCommand[] Returns an array of QuantityOnCommand objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuantityOnCommand
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
