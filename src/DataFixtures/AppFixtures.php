<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Color;
use App\Entity\Product;
use App\Entity\Size;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        // load sizes
        $sizes = ['XS', 'S', 'M', 'L', 'Xl'];
        $sizesObj = [];
        foreach($sizes as $item) {
            $size = new Size();
            $size->setName($item);

            $sizesObj[] = $size;
            $manager->persist($size);
        }

        // load colors
        $colors = ['blanc', 'noir', 'rouge'];
        $colorsObj = [];
        foreach($colors as $item) {
            $color = new Color();
            $color->setName($item);

            $colorsObj[] = $color;
            $manager->persist($color);
        } 

        // load category
        $categories = ['Hommes', 'Femmes', 'Streetwear','Geek_mangas','Staykawaii'];
        $categoriesObj = [];
        foreach($categories as $item) {
            $category = new Category();
            $category->setTitle($item);

            $categoriesObj[] = $category;
            $manager->persist($category);
        } 

        for ($i = 0; $i < 15; $i++) {
            $product = new Product();
            $product->setTitle($faker->word);
            $product->setDescription($faker->paragraph);
            $product->setPrice($faker->numberBetween(1, 60));
            $product->setQuantity($faker->numberBetween(1, 20));
            $product->setCategory($faker->randomElement($categoriesObj));
            $product->setSize($faker->randomElement($sizesObj));
            $product->setColor($faker->randomElement($colorsObj));

            $manager->persist($product);
        }

        // create user Admin
        $admin = new User();
        $admin->setEmail('admin@gmail.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));

        $manager->persist($admin);

        // create 10 user Seller
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('user' . ($i + 1) . '@gmail.com');
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->hasher->hashPassword($user, 'password'));

            $manager->persist($user);
        }


        $manager->flush();
    }
}
