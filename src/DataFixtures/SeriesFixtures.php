<?php

namespace App\DataFixtures;

use App\Entity\CatRecette;
use App\Entity\Film;
use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SeriesFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Création de séries
//        for ($j = 0; $j < 5; ++$j) {
//            $serie = new Film();
//            $serie->setUpdatedAt(new \DateTimeImmutable());
//            $serie->setCreatedAt(new \DateTimeImmutable());
//            $serie->setTitre($faker->text(50));
//            $serie->setMedia($faker->imageUrl(200, 200, 'movies', true));
//
//            $randomCat = rand(0, count($cats) - 1);
//            $serie->se($cats[$randomCat]);
//
//            $manager->persist($serie);
//
//        }

        $manager->flush();
    }
}
