<?php

namespace App\DataFixtures;

use Faker;
use Faker\Factory;
use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {

    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $admin = new Users();
        $admin->setEmail('pinchon@mail.fr')
                ->setLastname('Pinchon')
                ->setName('Lucas')
                ->setAddresse('44 rue du Bellay')
                ->setZipcode('80000')
                ->setCity('Amiens')
                ->setPassword(
                    $this->passwordEncoder->hashPassword($admin, 'azerty')
                )
                ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        for($i = 1; $i <= 5; $i++){
            $user = new Users();
            $user->setEmail($faker->email())
                    ->setLastname($faker->lastName())
                    ->setName($faker->firstName())
                    ->setAddresse($faker->streetAddress())
                    ->setZipcode(str_replace(' ', '', $faker->postcode()))
                    ->setCity($faker->city())
                    ->setPassword(
                        $this->passwordEncoder->hashPassword($user, 'user')
                    );
    
            $manager->persist($user);
        }

        $manager->flush();
    }
}
