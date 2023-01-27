<?php

namespace App\DataFixtures;

use App\Entity\Plat;
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
        for ($i = 0; $i < 50; $i++) {
            $plat = new Plat();
            $plat->setName($this->faker->word())
                ->setPrice(mt_rand(0, 30))
                ->setDescription('hamburger specialité de saavoie')
                ->setImage('https://img.passeportsante.net/1000x526/2021-03-16/i100351-hamburger-maison.jpeg');

            $manager->persist($plat);
        }

        $manager->flush();
    }
}
