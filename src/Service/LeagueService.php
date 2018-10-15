<?php

namespace App\Service;

use Api\Exception\ApiException;
use Api\Util\DefaultConstant;
use App\Entity\League;
use App\Entity\Team;
use Doctrine\ORM\EntityManagerInterface;

class LeagueService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param League $league
     * @param int    $offset
     * @param int    $limit
     *
     * @return array
     */
    public function teams(League $league, $offset = DefaultConstant::DEFAULT_OFFSET, $limit = DefaultConstant::DEFAULT_OFFSET): array
    {
        return $this->em->getRepository(Team::class)->findBy(['league' => $league], ['name' => 'ASC'], $limit, $offset);
    }

    /**
     * @param League $league
     */
    public function delete(League $league)
    {
        try {
            $this->em->remove($league);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }
}