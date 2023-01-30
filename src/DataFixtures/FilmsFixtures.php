<?php

namespace App\DataFixtures;

use App\Entity\CatFilm;
use App\Entity\CatRecette;
use App\Entity\Film;
use App\Entity\Recette;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class FilmsFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        //      Création de catégories
        $names = [
            'Action',
            'Thriller',
            'Comédie',
            'S.F.',
            'Drame',
            'D.A.',
        ];
        $cats = [];
        for ($i = 0; $i < count($names); ++$i) {
            $cats[$i] = new CatFilm();
            $cats[$i]->setName($names[$i]);
            $manager->persist($cats[$i]);
        }

        // Création de videos_films
        for ($j = 0; $j < 5; ++$j) {
            $film = new Film();
            $film->setUpdatedAt(new \DateTimeImmutable());
            $film->setCreatedAt(new \DateTimeImmutable());
            $film->setTitre($faker->text(20));
            $film->setMedia($faker->imageUrl(200, 200, 'movies', true));
            $film->setVu($faker->boolean);
            $film->setAGarder($faker->boolean);
            $film->setCoupDeCoeur($faker->boolean);
            $film->setAnneeSortie($faker->year());

            $randomCat = rand(0, count($cats) - 1);
            $film->addCategory($cats[$randomCat]);

            $manager->persist($film);

        }

        $manager->flush();
    }
}
