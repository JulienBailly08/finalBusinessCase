<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }


    /**
    * @return Product les 3 produits similaires
    */
    public function similarProducts($produit)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.category = :Cat')
            ->andWhere('p.id != :id')
            ->setParameter('Cat', $produit->getCategory()->getId())
            ->setParameter('id', $produit->getId())
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
    * @return Product Produits phares
    */
    public function likedProducts()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.putInFront = 1')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findProductByName(string $query)
    {
        $qb = $this->createQueryBuilder('p');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('p.title', ':query'),
                        $qb->expr()->like('p.description', ':query'),
                    ),
                )
            )          
            ->setParameter('query', '%' . $query . '%')
            ->andWhere('p.isActive = 1')
        ;
        return $qb
            ->getQuery()
            ->getResult();
    }





    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
