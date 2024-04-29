<?php

namespace App\Repository;

use App\Entity\Art;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Art>
 *
 * @method Art|null find($id, $lockMode = null, $lockVersion = null)
 * @method Art|null findOneBy(array $criteria, array $orderBy = null)
 * @method Art[]    findAll()
 * @method Art[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Art::class);
    }

    public function findByCriteria($criteria,$sortBy = null)
    {
        $queryBuilder = $this->createQueryBuilder('a');
    
        // Add conditions based on criteria
        if (isset($criteria['artTitle'])) {
            $queryBuilder->andWhere('a.artTitle LIKE :artTitle')
                ->setParameter('artTitle', '%' . $criteria['artTitle'] . '%');
        }
    
        if (isset($criteria['type'])) {
            $queryBuilder->andWhere('a.type LIKE :type')
                ->setParameter('type', $criteria['type']);
        }
    
        // Add more conditions if needed for other properties of the Art entity
        if ($sortBy) {
            foreach ($sortBy as $field => $direction) {
                $queryBuilder->addOrderBy('a.' . $field, $direction);
            }
        }
    
        
        // Execute the query
        $query = $queryBuilder->getQuery();
    
        // Return the filtered results
        return $query->getResult();
    }
    

}
