<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [
        ['number' => '1', 'synopsis' => 'Suit la vie de huit couples très différents qui gèrent leur vie amoureuse dans divers récits vaguement interdépendants, le tout se déroulant pendant la période frénétique de Noël à Londres.', 'category' => 'category_Romance'],
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::SEASONS as $seasonNumber) {
            $season = new Season();
            $season->setNumber(1);
            $season->setProgram($this->getReference('program_Breaking Bad');
            $this->addReference('season1_Arcane', $season);
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return [
          CategoryFixtures::class,
        ];
    }
}

