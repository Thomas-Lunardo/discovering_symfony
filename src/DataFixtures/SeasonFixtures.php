<?php

namespace App\DataFixtures;

use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public const SEASONS = [
        ['number' => '1', 'year' => '2008', 'description' => 'Diagnosed with terminal lung cancer, chemistry teacher Walter White teams up with former student Jesse Pinkman to cook and sell crystal meth.', 'program' => 'program_Breaking Bad'],
        ['number' => '2', 'year' => '2009', 'description' => 'Walt and Jesse realize how dire their situation is. They must come up with a plan to kill Tuco before Tuco kills them first.', 'program' => 'program_Breaking Bad'],
        ['number' => '3', 'year' => '2010', 'description' => 'Skyler goes through with her plans to divorce Walt. Jesse finishes rehab.', 'program' => 'program_Breaking Bad'],
        ['number' => '4', 'year' => '2011', 'description' => 'Walt and Jesse are held captive by Gus, after Gale\'s death. Meanwhile, Skyler tries to find out what happened to Walt.', 'program' => 'program_Breaking Bad'],
        ['number' => '5', 'year' => '2012', 'description' => 'Now that Gus is dead, Walt, Jesse, and Mike work to cover their tracks. Skyler panics when Ted Beneke wakes up.', 'program' => 'program_Breaking Bad'],
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::SEASONS as $seasonList) {
            $season = new Season();
            $season->setNumber($seasonList['number']);
            $season->setYear($seasonList['year']);
            $season->setDescription($seasonList['description']);
            $season->setProgram($this->getReference($seasonList['program']));
            $manager->persist($season);
            $this->addReference('season_'. $seasonList['number'] . '_' . $season->getProgram()->getTitle(), $season);
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}

