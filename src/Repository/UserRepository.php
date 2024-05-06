<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByCriteria($criteria,$sortBy = null)
    {
        $queryBuilder = $this->createQueryBuilder('u');
    
        // Add conditions based on criteria
        if (isset($criteria['fname'])) {
            $queryBuilder->andWhere('u.fname LIKE :fname')
                ->setParameter('fname', '%' . $criteria['fname'] . '%');
        }

        if (isset($criteria['lname'])) {
            $queryBuilder->andWhere('u.lname LIKE :lname')
                ->setParameter('lname', '%' . $criteria['lname'] . '%');
        }
    
        if (isset($criteria['role'])) {
            $queryBuilder->andWhere('u.role LIKE :role')
                ->setParameter('role', $criteria['role']);
        }
    
        // Add more conditions if needed for other properties of the Art entity
        if ($sortBy) {
            foreach ($sortBy as $field => $direction) {
                $queryBuilder->addOrderBy('u.' . $field, $direction);
            }
        }
    
        
        // Execute the query
        $query = $queryBuilder->getQuery();
    
        // Return the filtered results
        return $query->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
