<?php

namespace App\Repository;

use App\Entity\DeliveryAgency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeliveryAgency>
 *
 * @method DeliveryAgency|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryAgency|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryAgency[]    findAll()
 * @method DeliveryAgency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryAgencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryAgency::class);
    }

//    /**
//     * @return DeliveryAgency[] Returns an array of DeliveryAgency objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DeliveryAgency
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
