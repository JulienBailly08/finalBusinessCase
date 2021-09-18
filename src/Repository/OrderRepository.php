<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function findOrdersPaid($value): ?Order // a retravailler => return un array et non une collection d'objet !
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :val')
            ->andWhere('o.isPaid = 1')
            ->setParameter('val', $value)
            ->orderBy('o.createdAt', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function findOneBySomeField($value1, $value2): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :val1')
            ->andWhere('o.createdAt = :val2')
            ->andWhere('o.isPaid = 0')
            ->setParameter('val1', $value1)
            ->setParameter('val2', $value2)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findOneBySomeFieldSimple($value1): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.user = :val1') 
            ->andWhere('o.isPaid = 0')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
