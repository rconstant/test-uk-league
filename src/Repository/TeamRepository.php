<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class TeamRepository extends EntityRepository
{
    /**
     * @param array $criteria
     *
     * @return mixed
     */
    public function findByNameAndNotSame(array $criteria)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('T')
            ->from('App:Team', 'T')
            ->where($qb->expr()->eq('T.name', ':name'))
            ->andWhere($qb->expr()->neq('T.id', ':id'))
            ->setParameters($criteria)
        ;
        return $qb->getQuery()->getResult();
    }
}