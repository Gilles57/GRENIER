<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Film;
use App\Entity\Langue;
use App\Entity\Version;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmsFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


//        //      Création de genres
//        $namesGenres = [
//            'Action',
//            'Thriller',
//            'Comédie',
//            'S.F.',
//            'Drame',
//            'D.A.',
//        ];
//        $genres = [];
//        for ($i = 0; $i < count($namesGenres); ++$i) {
//            $genres[$i] = new Genre();
//            $genres[$i]->setName($namesGenres[$i]);
//            $manager->persist($genres[$i]);
//        }
//
//        //      Création de langues
//        $namesLangues = [
//            'English',
//            'Français',
//            'Spañol',
//            'Allemand',
//        ];
//        $langues = [];
//        for ($i = 0; $i < count($namesLangues); ++$i) {
//            $langues[$i] = new Langue();
//            $langues[$i]->setName($namesLangues[$i]);
//            $manager->persist($langues[$i]);
//        }
//
//        //      Création de versions
//        $namesVersions = [
//            'VF',
//            'VO',
//            'VOSTFR',
//            'MULTi',
//        ];
//        $versions = [];
//        for ($i = 0; $i < count($namesVersions); ++$i) {
//            $versions[$i] = new Version();
//            $versions[$i]->setName($namesVersions[$i]);
//            $manager->persist($versions[$i]);
//        }
//
//        // Création de videos_films
//        for ($j = 0; $j < 5; ++$j) {
//            $film = new Film();
//            $film->setUpdatedAt(new \DateTimeImmutable());
//            $film->setCreatedAt(new \DateTimeImmutable());
//            $film->setTitre($faker->text(20));
//            $film->setTitreOriginal($faker->text(20));
//            $film->setReleaseDate(new \DateTimeImmutable());
//            $film->setMedia($faker->imageUrl(200, 200, 'movies', true));
//            $film->setExtension('.'.$faker->text(5));
//            $film->setCodeTmbd($faker->uuid());
//            $film->setCommentaires($faker->text);
//            $film->setVu($faker->boolean);
//            $film->setAGarder($faker->boolean);
//            $film->setCoupDeCoeur($faker->boolean);
//            $film->setARemplacer($faker->boolean);
//
//
//            $manager->persist($film);
//
//            $film->addVersion($versions[rand(0, count($versions) - 1)]);
//
//            for ($j = 0; $j < $faker->numberBetween(0, count($namesGenres)); ++$j) {
//                $film->addGenre($genres[rand(0, count($namesGenres) - 1)]);
//            }
//            for ($j = 0; $j < $faker->numberBetween(0, count($namesLangues)); ++$j) {
//                $film->addLangue($langues[rand(0, count($namesLangues) - 1)]);
//            }
//            $film->setCoupDeCoeur('false');
//            $film->setAGarder('false');
//            $film->setARemplacer('false');
//            $film->setVu('false');
//
//        }

        $manager->flush();
    }
}
