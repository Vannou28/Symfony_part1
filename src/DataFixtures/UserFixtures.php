<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        // Création d’un utilisateur de type “contributeur” (= auteur)
        $contributor = new User();
        $contributor->setEmail('contributor@monsite.com');
        $contributor->setRoles(['ROLE_CONTRIBUTOR']);
        $contributor->setPassword($this->passwordEncoder->encodePassword(
            $contributor,
            'toto'
        ));

        $manager->persist($contributor);

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'toto'
        ));
        $manager->persist($admin);

        for($i=0;$i<30;$i++){
                    // Création d’un utilisateur de type “administrateur”
            $userFaker = new User();
            $userFaker->setEmail($faker->email());
            $userFaker->setRoles(['ROLE_ADMIN']);
            $userFaker->setPassword($this->passwordEncoder->encodePassword(
                $admin,
                'Toto'
            ));
            $manager->persist($userFaker);
        }

        

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }
}
