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
        return $this->createQueryBuilder('service')
            ->select('DISTINCT service')
            ->join('service.pizzaServiceSlots', 'slot')
            ->leftJoin('slot.reservations', 'reservation', 'WITH', 'reservation.status IN (:statuses)')
            ->leftJoin('reservation.reservationItems', 'item')
            ->andWhere('service.serviceDate > :tomorrow')
            ->setParameter('tomorrow', new \DateTimeImmutable('tomorrow'))
            ->setParameter('statuses', ['PENDING', 'CONFIRMED'])
            ->andWhere('service.bookingOpen = true')
            ->groupBy('service.id', 'slot.id', 'slot.capacity')
            ->having('slot.capacity > COALESCE(SUM(item.quantity), 0)')
            ->orderBy('service.serviceDate', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
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
