<?php

namespace App\DataFixtures;

use App\Entity\League;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LeagueFixture extends AbstractFixture
{
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