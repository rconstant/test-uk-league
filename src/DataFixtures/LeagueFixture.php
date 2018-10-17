<?php

namespace App\DataFixtures;

use App\Entity\League;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LeagueFixture
 * @package App\DataFixtures
 */
class LeagueFixture extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $league = new League('Premier League');
        $manager->persist($league);
        $manager->persist(new League('English Football League Championship'));
        $manager->persist(new League('English Football League One'));
        $manager->persist(new League('English Football League Two'));
        $manager->persist(new League('National League'));
        $manager->flush();

        $this->addReference('league', $league);
    }
}