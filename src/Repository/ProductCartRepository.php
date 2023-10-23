<?php

namespace App\Repository;

use App\Entity\ProductCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCart>
 *
 * @method ProductCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCart[]    findAll()
 * @method ProductCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCart::class);
    }

    public function save(ProductCart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductCart $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findProductCartById($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    public function findProductCartsByProductAndCart($product, $cart)
    {
        return $this->createQueryBuilder('p')
        ->leftJoin('p.product', 'pd')
        ->leftJoin('p.cart', 'ct')
        ->andWhere('pd = :product AND ct = :cart')
        ->setParameter('product', $product)
        ->setParameter('cart', $cart)
        ->setMaxResults(1)
        ->getQuery()
        ->getResult();
    }

//    /**
//     * @return ProductCart[] Returns an array of ProductCart objects
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

//    public function findOneBySomeField($value): ?ProductCart
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
