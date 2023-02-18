<?php

namespace App\DataFixtures;

use App\Entity\Plat;
use App\Entity\Menu;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr-FR');
    }
    public function load(ObjectManager $manager): void
    {
        // Plat
        $plats = [];
        for ($i = 0; $i < 50; $i++) {
            $plat = new Plat();
            $plat->setName($this->faker->word())
                ->setPrice(mt_rand(0, 30))
                ->setDescription($this->faker->text(200))
                ->setImage('https://img.passeportsante.net/1000x526/2021-03-16/i100351-hamburger-maison.jpeg')
                ->setCategory(mt_rand(0, 30));

            $plats[] = $plat;
            $manager->persist($plat);
        }

        // Menu
        for ($j=0; $j < 10; $j++) { 
            $menu = new Menu();
            $menu->setName($this->faker->word())
            ->setFormule(mt_rand(0, 30))
            ->setPrice(mt_rand(0, 30))
            ->setDescription($this->faker->text(200));

            for ($k=0; $k < mt_rand(5, 20); $k++) { 
                $menu->addPlat($plats[mt_rand(0, count($plats) - 1)]);
            }
            $manager->persist($menu);
        }

        $manager->flush();
    }
}
