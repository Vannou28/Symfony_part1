<?php

namespace App\DataFixtures;

use App\Entity\Actor;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActorFixtures extends Fixture
{
    const ACTORS = ['Al Pacino',
                    'Robert De Niro',
                    'Leonardo DiCaprio',
                    'Kevin Spacey',
                    'Clint Eastwood',
                    'Morgan Freeman',
                    'Johnny Depp',
                    'Catherine Zeta-Jones',
                    'Alice Taglioni',
                    'Jane Levy',
                    'Penélope Cruz',
                    'Eva Longoria',
                    'Alice David',
                    ];

                
    public function load(ObjectManager $manager)
    {
        foreach (self::ACTORS as $key => $actorName){
            $actor = new Actor();
            $actor->setName($actorName);
            $manager->persist($actor);
            $this->addReference('actor_' . $key, $actor);
        }
        $manager->flush();
    }
}
