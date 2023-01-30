<?php

namespace App\DataFixtures;

use App\Entity\CatRecette;
use App\Entity\Recette;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RecettesFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');


        //      Création de catégories
        $names = [
            'Entrées',
            'Plats',
            'Desserts',
            'Accompagnements',
            'Plats uniques',
            'Boissons',
        ];
        $cats = [];
        for ($i = 0; $i < count($names); ++$i) {
            $cats[$i] = new CatRecette();
            $cats[$i]->setName($names[$i]);
            $manager->persist($cats[$i]);
        }

        // Création de recettes
        for ($j = 0; $j < 5; ++$j) {
            $recette = new Recette();
            $recette->setUpdatedAt(new \DateTimeImmutable());
            $recette->setCreatedAt(new \DateTimeImmutable());
            $recette->setTitre($faker->text(50));
            $recette->setdureePreparation($faker->numberBetween(10, 200));
            $recette->setIllustration('secteur_recette-test-'.($j + 1).'.jpg');

            $randomCat = rand(0, count($cats) - 1);
            $recette->setCategorie($cats[$randomCat]);

            $manager->persist($recette);

//            $manager->persist($user);
        }

        $manager->flush();
    }
}
