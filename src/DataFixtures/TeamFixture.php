<?php

namespace App\DataFixtures;

use App\Entity\LeagueInterface;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TeamFixture extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $league = $this->getReference('league');
        if ($league instanceof LeagueInterface) {
            $manager->persist(new Team('Man City', $league, "white"));
            $manager->persist(new Team('Chelsea', $league, "red"));
            $manager->persist(new Team('Liverpool', $league));
            $manager->persist(new Team('Arsenal', $league));
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            LeagueFixture::class
        ];
    }
}