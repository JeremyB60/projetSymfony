<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findDistinctTypes()
    {
        $query = $this->createQueryBuilder('p')
            ->select('DISTINCT p.type')
            ->orderBy('p.type', 'ASC')
            ->getQuery();

        $result = $query->getResult();

        $types = [];
        foreach ($result as $item) {
            $types[] = $item['type'];
        }

        return $types;
    }

    public function findByType(string $type): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }
}
