<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    const CATEGORIES = [
        'Action',
        'Aventure',
        'Animation',
        'Fantasitque',
        'Horreur',
    ];
    public function load(ObjectManager $manager)
    {


    foreach (self::CATEGORIES as $key => $categoryName) {
        $category = new Category();
        $category->setName($categoryName);

        $manager->persist($category);
    }
    $manager->flush();

        for ($i = 1; $i <= 50; $i++) {  
            $category = new Category();  
            $category->setName('Nom de catégorie ' . $i);  
            $manager->persist($category);  
        }  
        $manager->flush();
    }
}