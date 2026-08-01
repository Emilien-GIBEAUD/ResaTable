<?php

namespace App\Repository;

use App\Entity\PizzaService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PizzaService>
 */
class PizzaServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PizzaService::class);
    }

    /**
        * @return PizzaService[] Returns an array of PizzaService objects
        */
    public function findAfterTomorrow(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.serviceDate  >= :tomorrow')
            ->setParameter('tomorrow', new \DateTimeImmutable('tomorrow'))
            ->andWhere('p.bookingOpen = true')
            ->orderBy('p.serviceDate', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?PizzaService
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
