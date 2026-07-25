<?php

namespace App\Repository;

use App\Entity\Pizza;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pizza>
 */
class PizzaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pizza::class);
    }

    /**
        * @return Pizza[] Returns an array of Pizza objects
        */
    public function findByFilters(int $showActive, string $sort, string $direction): array
    {
        $qb = $this->createQueryBuilder('p');

        if ($showActive === 0) {
            $qb->andWhere('p.isActive = :active')
            ->setParameter('active', false);
        }
        if ($showActive === 1) {
            $qb->andWhere('p.isActive = :active')
            ->setParameter('active', true);
        }

        $qb->orderBy('p.' . $sort, $direction);

        return $qb->getQuery()->getResult();
    }

//    public function findOneBySomeField($value): ?Pizza
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
