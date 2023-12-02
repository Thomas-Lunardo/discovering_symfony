<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        foreach (ProgramFixtures::PROGRAMES as $programList) {
            for($seasonNumber = 1; $seasonNumber < 6; $seasonNumber++){
                for ($i = 1; $i < 11; $i++) {
                $episode = new Episode();
                $episode->setTitle($faker->word());
                $episode->setNumber($i);
                $episode->setSynopsis($faker->sentence());
                $episode->setSeason($this->getReference('program_' . $programList['title'] . 'season_' . $seasonNumber));
                $manager->persist($episode);
            }
        }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}
