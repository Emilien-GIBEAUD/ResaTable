<?php

namespace App\Repository;

use App\Entity\PizzaServiceTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PizzaServiceTemplate>
 */
class PizzaServiceTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PizzaServiceTemplate::class);
    }

    /**
    * @return PizzaServiceTemplate[] Returns an array of PizzaServiceTemplate objects
    */
    public function findByActive(int $showActive): array
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

        return $qb->getQuery()->getResult();
    }

    //    public function findOneBySomeField($value): ?PizzaServiceTemplate
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
