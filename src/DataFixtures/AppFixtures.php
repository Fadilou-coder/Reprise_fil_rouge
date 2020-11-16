<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Profil;
use App\Entity\User;

class AppFixtures extends Fixture
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $profilTab = ['Administrateur','CM','Formateur', 'Apprenant'];
        foreach ($profilTab as $libelle) {
            $profil = new Profil() ;
            $profil ->setLibelle ($libelle );
            $manager ->persist($profil);
            for ($i=1; $i <=3 ; $i++) {
                $user = new User();
                $user ->setProfil ($profil)
                      ->setPrenom($faker->firstName())
                      ->setNom($faker->lastName)
                      ->setEmail($faker->email);
                $password = $this->encoder->encodePassword ($user, 'pass_1234' );
                $user ->setPassword ($password );
                $manager ->persist($user);
            }
        
        }
        $manager->flush();
    }

}