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
        
        $parent = new Categories();
        $parent->setName('Categorie1')
                ->setImage($faker->imageUrl(300,300));
        $manager->persist($parent);

        for($i = 2; $i <=5; $i++) {
            $categorie = new Categories();
            $categorie->setName('Categorie'.$i)
                    ->setImage($faker->imageUrl(300, 300))
                    ->setParent($parent);

            $this->addReference('cat-'.$this->counter, $categorie);
            $this->counter++;

            $manager->persist($categorie);
        }
        
        $manager->flush();
    }
}
