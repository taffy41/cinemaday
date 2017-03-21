<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Cinema;
use AppBundle\Entity\Date;
use AppBundle\Entity\Performance;

/**
 * PerformanceRepository
 */
class PerformanceRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param Date   $date
     * @param Cinema $cinema
     *
     * @return Performance[]
     */
    public function fetchByDateAndCinema(Date $date, Cinema $cinema) : array
    {
        $qb = $this->createQueryBuilder('p');
        return $qb->select('p, d, c, f')
            ->from(Performance::class, 'p')
            ->innerJoin('p.film', 'f')
            ->innerJoin('p.date', 'd')
            ->innerJoin('p.cinema', 'c')
            ->andWhere($qb->expr()->eq('d', ':date'))
            ->andWhere($qb->expr()->eq('c', ':cinema'))
            ->setParameter('date', $date)
            ->setParameter('cinema', $cinema)
            ->addOrderBy('p.time', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
