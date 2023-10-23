<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
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

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findProductsBySkinTypeAndSkinProblem($skinType, $skinProblem)
    {
        return $this->createQueryBuilder('p')
        ->leftJoin('p.problems', 'pb')
        ->andWhere('pb.skintype LIKE :skinType AND pb.skinproblem LIKE :skinProblem')
        ->setParameter('skinType', '%'.$skinType.'%')
        ->setParameter('skinProblem','%'. $skinProblem.'%')
        ->orderBy('p.id', 'DESC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult();
    }

        
    public function findProductById($id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
    * @return Product[] Returns an array of Product objects
    */
    public function findOneBybenefits($benefits)
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.productProperties', 'pp')
            ->leftJoin('pp.property', 'pr')
            ->andWhere('pr.benefits = :benefits')
            ->setParameter('benefits', $benefits)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findAllProducts()
    {
        return $this->createQueryBuilder('p')
        ->getQuery()
        ->getResult() ;
    }


//    /**
//     * @return Product[] Returns an array of Product objects
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

//    public function findOneByProperty($id): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.productProperties = :productProperties')
//            ->setParameter('id', $id)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


}
