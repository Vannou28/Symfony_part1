<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {  
        for ($i=0; $i < 6 ; $i++) {
            $program = new Program();
            $program->setTitle('Serie'.$i);
            $program->setSummary('Description Serie'.$i);
            $program->setPoster('https://picsum.photos/200');
            $program->setCategory($this->getReference('category_'.$i));
            //ici les acteurs sont insérés via une boucle pour être DRY mais ce n'est pas obligatoire
            for ($j=0; $j < 7 ; $j++) {
                $program->addActor($this->getReference('actor_' . $j));
            }
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
        ];
    }

}
