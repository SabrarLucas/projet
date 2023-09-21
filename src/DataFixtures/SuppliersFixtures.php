<?php

namespace App\DataFixtures;

use App\Entity\Suppliers;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SuppliersFixtures extends Fixture
{
    private $counter = 1;

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for($i = 1; $i <= 10; $i++){
            $suppliers = new Suppliers();
            
            $suppliers->setName($faker->name());

            $this->addReference('sup-'.$this->counter, $suppliers);
            $this->counter++;

            $manager->persist($suppliers);
        }
        
        $manager->flush();
    }
}
