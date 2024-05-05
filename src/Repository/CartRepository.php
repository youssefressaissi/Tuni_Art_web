<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 *
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }
    public function findAllSortedByUid(): array
{
    return $this->createQueryBuilder('c')
        ->orderBy('c.uid', 'ASC')
        ->getQuery()
        ->getResult();
}
public function findByUid(int $uid): array
{
    return $this->createQueryBuilder('c')
        ->andWhere('c.uid = :uid')
        ->setParameter('uid', $uid)
        ->getQuery()
        ->getResult();
}

 public function findAllAscending(): array
    {
        return $this->createQueryBuilder('R')
            ->orderBy('R.uid', 'ASC') // Replace 'fieldToSortBy' with the actual field name you want to sort by
            ->getQuery()
            ->getResult();
    }

    public function findAllDescending(): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.uid', 'DESC') // Replace 'fieldToSortBy' with the actual field name you want to sort by
            ->getQuery()
            ->getResult();
    }
    
//    /**
//     * @return Cart[] Returns an array of Cart objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Cart
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
