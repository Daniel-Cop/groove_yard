<?php

namespace App\Repository;

use App\Entity\Inventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inventory>
 */
class InventoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inventory::class);
    }

    public function findByIntention($parameter)
    {
        $query = $this->createQueryBuilder('i')
            ->addSelect('i') // to make Doctrine actually use the join
            ->leftJoin('i.intention', 'e')
            ->where('e.name = :intention')
            ->setParameter('intention', $parameter)
            ->getQuery();

        return $query->getResult();
    }

    public function findByAlbumToSell($intention, $album)
    {
        $query = $this->createQueryBuilder('i')
            ->addSelect('i') 
            ->join('i.intention', 'e')
            ->join('i.album', 'a')
            ->andWhere('e.name = :intention')
            ->andWhere('a.id = :id')
            ->setParameter('intention', $intention)
            ->setParameter('id', $album->getId())
            ->getQuery();

        return $query->getResult();
    }

    public function findByMarketFilter(string $intention, array $boundingBox, $userId, string  $searchValue)
    {
        $query = $this->createQueryBuilder('i')
        ->addSelect('i') 
        ->join('i.intention', 'e')
        ->join('i.album', 'a')
        ->join('i.user', 'u')
        ->join('u.address', 'c')
        ->andWhere('e.name = :intention')
        ->andWhere('a.title LIKE :value OR a.artist LIKE :value')
        ->andWhere('NOT u.id = :id')
        ->andWhere('(c.latitude + 0) > :minLat AND (c.latitude + 0) < :maxLat')
        ->andWhere('(c.longitude + 0) > :minLon AND (c.longitude + 0)< :maxLon')
        ->setParameter('intention', $intention)
        ->setParameter(':value', '%' . $searchValue . '%')
        ->setParameter(':id', $userId)
        ->setParameter(':minLat', $boundingBox['minLat'])
        ->setParameter(':maxLat', $boundingBox['maxLat'])
        ->setParameter(':minLon', $boundingBox['minLon'])
        ->setParameter(':maxLon', $boundingBox['maxLon'])
        ->orderBy('a.title', 'ASC')
        ->getQuery();

        return $query;
    }
}
