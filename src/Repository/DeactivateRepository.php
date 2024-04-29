<?php

namespace App\Repository;

use App\Entity\Deactivate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deactivate>
 *
 * @method Deactivate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deactivate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deactivate[]    findAll()
 * @method Deactivate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeactivateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deactivate::class);
    }

//    /**
//     * @return Deactivate[] Returns an array of Deactivate objects
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

//    public function findOneBySomeField($value): ?Deactivate
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
