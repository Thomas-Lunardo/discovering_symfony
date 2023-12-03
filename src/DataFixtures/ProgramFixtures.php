<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public const PROGRAMES = [
        ['title' => 'Love Actually', 'synopsis' => 'Suit la vie de huit couples très différents qui gèrent leur vie amoureuse dans divers récits vaguement interdépendants, le tout se déroulant pendant la période frénétique de Noël à Londres.', 'category' => 'category_Romance', 'poster' => 'build/images/love_actually.48f3179f.png',],
        ['title' => 'Transformers: Rise of the Beasts', 'synopsis' => 'Il suit à nouveau un groupe de Decepticons et d\'Autobots qui s\'affrontent dans une guerre entre ceux qui veulent contrôler la planète et ceux qui veulent vivre en communion.', 'category' => 'category_Sicence-fiction', 'poster' => 'build/images/tranformer_rise_of_the_beasts.e75706f0.png',],
        ['title' => 'Hunger Games', 'synopsis' => 'Katniss Everdeen se porte volontaire pour prendre la place de sa jeune soeur aux Hunger Games, une compétition télévisée au cours de laquelle deux adolescents de chacun des douze districts de Panem sont choisis au hasard pour se battre jusqu\'à la mort.', 'category' => 'category_Action', 'poster' => 'build/images/hunger_games.65c5f0f7.png',],
        ['title' => 'Napoléon', 'synopsis' => 'Offre un aperçu personnel des origines de Napoléon et de son ascension rapide et impitoyable vers l\'empire, vu à travers le prisme de sa relation addictive et souvent volatile avec sa femme et son véritable amour, Joséphine.', 'category' => 'category_Biopic', 'poster' => 'build/images/napoleon.cbe2c0c6.png',],
        ['title' => 'Self Control', 'synopsis' => 'A la suite d\'un malentendu qui dérape dans un avion, le timide David Buznik se retrouve condamné à suivre une thérapie de groupe de gestion des pulsions de colère. Après une première séance peu concluante, le docteur psychopathe et caractériel convainc le juge de le suivre à domicile.', 'category' => 'category_Humour', 'poster' => 'build/images/self_control.af7011fe.png',],
        ['title' => 'Breaking Bad', 'synopsis' => 'Un professeur de chimie de lycée chez qui on a diagnostiqué un cancer du poumon inopérable se tourne vers la fabrication et la vente de méthamphétamine pour assurer l\'avenir de sa famille.', 'category' => 'category_Policier', 'poster' => 'build/images/breaking_bad.bde4b71b.png',],
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::PROGRAMES as $programName) {
            $program = new Program();
            $program->setTitle($programName['title']);
            $program->setSynopsis($programName['synopsis']);
            $program->setCategory($this->getReference($programName['category']));
            if (array_key_exists('poster', $programName)) {
                $program->setPoster($programName['poster']);
            }
            $manager->persist($program);
            $this->addReference('program_' . $programName['title'], $program);
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

