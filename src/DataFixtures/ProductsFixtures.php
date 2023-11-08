<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductsFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            SuppliersFixtures::class
        ];
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 1; $i <= 20; $i++) {
            $product = new Products();
            $product->setName($faker->text(10))
                ->setDescription($faker->text())
                ->setPrice($faker->numberBetween(20, 100))
                ->setImage($faker->imageUrl(300, 300));

            $category = $this->getReference('cat-' . rand(2, 4));
            $product->setCategories($category);

            $supplier = $this->getReference('sup-' . rand(1, 10));
            $product->setSuppId($supplier);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
