<?php

namespace App\DataFixtures;

use App\Service\Slugify;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;


class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {   $nbSeasonReference=0;
        for ($j=0; $j < 30 ; $j++) {
            for ($i=0; $i < 7 ; $i++) {
                $episode = new Episode();
                $episode->setSeason($this->getReference('season_'. $nbSeasonReference));
                $episode->setTitle('Episode '.$i);
                $slugify = new Slugify();
                $slug = $slugify->generate($episode->getTitle());
                $episode->setSlug($slug);
                $episode->setNumber($i);
                $episode->setSynopsis('Synopsis '.$i);
                $manager->persist($episode); 
            }
            $nbSeasonReference++;
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          SeasonFixtures::class,
        ];
    }
}
