<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const EPISODES = [
        ['title' => 'Pilot', 'number' => '1', 'synopsis' => 'Diagnosed with terminal lung cancer, chemistry teacher Walter White teams up with former student Jesse Pinkman to cook and sell crystal meth.', 'season' => 'season_1'],
        ['title' => 'Cat\'s in the Bag...', 'number' => '2', 'synopsis' => 'After their first drug deal goes terribly wrong, Walt and Jesse are forced to deal with a corpse and a prisoner. Meanwhile, Skyler grows suspicious of Walt\'s activities.', 'season' => 'season_1'],
        ['title' => 'And the Bag\'s in the River', 'number' => '3', 'synopsis' => 'Walt and Jesse clean up after the bathtub incident before Walt decides what course of action to take with their prisoner Krazy-8.', 'season' => 'season_1'],
        ['title' => 'Cancer Man', 'number' => '4', 'synopsis' => 'Walt tells the rest of his family about his cancer. Jesse tries to make amends with his own parents.', 'season' => 'season_1'],
        ['title' => 'Seven Thirty-Seven', 'number' => '1', 'synopsis' => 'Walt and Jesse realize how dire their situation is. They must come up with a plan to kill Tuco before Tuco kills them first.', 'season' => 'season_2']

    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::EPISODES as $episodeList) {
            $episode = new Episode();
            $episode->setNumber($episodeList['number']);
            $episode->setTitle($episodeList['title']);
            $episode->setSynopsis($episodeList['synopsis']);
            $episode->setSeason($this->getReference($episodeList['season']));
            $manager->persist($episode);
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
