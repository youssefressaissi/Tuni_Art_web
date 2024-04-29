<?php

namespace App\Repository;

use App\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gallery>
 *
 * @method Gallery|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gallery|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gallery[]    findAll()
 * @method Gallery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('g')
            ->getQuery()
            ->getResult();
    }

    public function searchByName( string $name): array
{
    return $this->createQueryBuilder('g')
        
        ->andWhere('g.galleryName LIKE :gallery_name')
        ->setParameter('gallery_name', '%' . $name . '%') // Use wildcard for partial match
        ->getQuery()
        ->getResult();
}
public function searchByLocation(string $location): array
{
    return $this->createQueryBuilder('g')
        ->andWhere('g.galleryLocation = :gallery_location')
        ->setParameter('gallery_location', $location)
        ->getQuery()
        ->getResult();
}
}

