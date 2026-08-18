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
}
