<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    public function findPendingExpired(\DateTimeImmutable $now): array
    {
        return $this->createQueryBuilder('reservation')
            ->andWhere('reservation.status = :status')
            ->andWhere('reservation.confirmationExpiresAt <= :now')
            ->setParameter('status', 'PENDING')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }

    public function findConfirmedOutdated(\DateTimeImmutable $now): array
    {
        $now->format('Y-m-d');
        return $this->createQueryBuilder('reservation')
            ->join('reservation.slot', 'slot')
            ->leftjoin('slot.service', 'service')
            ->andWhere('reservation.status = :status')
            ->andWhere('service.serviceDate < :now')
            ->setParameter('status', 'CONFIRMED')
            ->setParameter('now', $now)
            ->getQuery()
            ->getResult();
    }
}
