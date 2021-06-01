<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{   
    const CATEGORIES = [
        'Horreur',
        'Action',
        'Animation',
        'Aventure',
        'Biographique',
        'Catastrophe',
        'Comédie',
        'Comédie Dramatique',
        'Comédie Musicale',
        'Comédie Policière',
        'Comédie Romantique',
        'Court Métrage',
        'Dessin Animé',
        'Documentaire',
        'Drame',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $key => $cathegoryName){
            $category = new Category();
            $category->setName($cathegoryName);
            $manager->persist($category);
        }
        
        
        
        $manager->flush();
    }
}