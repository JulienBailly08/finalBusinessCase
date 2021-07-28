<?php

namespace App\Repository;

use App\Entity\ImgBanners;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImgBanners|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImgBanners|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImgBanners[]    findAll()
 * @method ImgBanners[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImgBannersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImgBanners::class);
    }

    // /**
    //  * @return ImgBanners[] Returns an array of ImgBanners objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImgBanners
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
