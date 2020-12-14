<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $this->seedCategories($manager, $faker);
        $this->seedProducts($manager, $faker);
    }

    private function seedCategories(ObjectManager $manager, \Faker\Generator $faker) {
        for ($i = 0; $i < 5; $i++) {
            $category = new Category();
            $category->setName($faker->text(12));
            $category->setDescription($faker->text(36));
            $manager->persist($category);
        }
        $manager->flush();
    }

    private function seedProducts(ObjectManager $manager, \Faker\Generator $faker) {
        $categories = $this->categoryRepository->findAll();
        for ($i = 0; $i < 25; $i++) {
            $product = new Product();
            $product->setName($faker->text(12));
            $product->setCategory($faker->randomElement($categories));
            $product->setPrice($faker->randomNumber(2));
            $product->setCurrency($faker->randomElement(["EUR", "USD"]));
            $product->setFeatured($faker->boolean(20));
            $manager->persist($product);
        }
        $manager->flush();
    }
}
