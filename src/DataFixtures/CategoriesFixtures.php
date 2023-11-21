<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoriesFixtures extends Fixture
{
    private $counter = 1;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        $coffee = new Categories();
        $coffee->setName('cafes')
                ->setImage($faker->imageUrl(300,300));
        $manager->persist($coffee);

        $teas = new Categories();
        $teas->setName('thes')
                ->setImage($faker->imageUrl(300,300));
        $manager->persist($teas);

        for($i = 0; $i < 5; $i++) {
            $categorie = new Categories();
            $categorie->setName('cafes' . $i + 1)
                    ->setImage($faker->imageUrl(300, 300))
                    ->setParent($coffee);

            $this->addReference('cat-'.$this->counter, $categorie);
            $this->counter++;

            $manager->persist($categorie);
        }

        for($i = 0; $i < 5; $i++) {
            $categorie = new Categories();
            $categorie->setName('thes' . $i + 1)
                    ->setImage($faker->imageUrl(300, 300))
                    ->setParent($teas);

            $this->addReference('cat-'.$this->counter, $categorie);
            $this->counter++;

            $manager->persist($categorie);
        }
        
        $manager->flush();
    }
}
