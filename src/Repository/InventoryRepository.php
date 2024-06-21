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
}
