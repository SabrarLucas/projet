<?php

namespace App\Repository;

use App\Entity\ProductsOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductsOrders>
 *
 * @method ProductsOrders|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsOrders|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsOrders[]    findAll()
 * @method ProductsOrders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsOrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductsOrders::class);
    }

//    /**
//     * @return ProductsOrders[] Returns an array of ProductsOrders objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductsOrders
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
