<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class LeagueRepository extends EntityRepository
{
    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findByNameAndNotSame(array $criteria)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('L')
            ->from('App:League', 'L')
            ->where($qb->expr()->eq('L.name', ':name'))
            ->andWhere($qb->expr()->neq('L.id', ':id'))
            ->setParameters($criteria)
        ;
        return $qb->getQuery()->getResult();
    }
}