<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        
        for($i = 1; $i <= 20; $i++){
            $product = new Products();
            $product->setName($faker->text(10))
                    ->setDescription($faker->text())
                    ->setPrice($faker->numberBetween(200, 1000))
                    ->setImage($faker->imageUrl(300, 300));

            $category = $this->getReference('cat-'. rand(2, 4));
            $product->setCategories($category);
            
            $manager->persist($product);
        }

        $manager->flush();
    }
}
