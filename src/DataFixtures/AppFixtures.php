<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $encoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

//        dd($faker->slug());
        $contact = new Users();
        $contact->setEmail('g.salmon@free.fr');
        $contact->setForename('Gilles');
        $contact->setName('SALMON');
        $contact->setIsVerified('true');
        $contact->setPassword($this->encoder->hashPassword($contact, 'sellig'));
        $contact->setRoles(['ROLE_ADMIN']);
        $manager->persist($contact);
        $moi = $contact;

//        // Création d'utilisateurs
//        for ($i = 1; $i <= 10; ++$i) {
//            $user = new User();
//            $user->setName($faker->name());
//            $user->setEmail($faker->email);
//            $user->setCreatedAt(new \DateTimeImmutable('now'));
//            $user->setUpdatedAt(new \DateTimeImmutable('now'));
//            $user->setPassword($this->encoder->hashPassword($user, $faker->password));
//            $user->setRoles(['ROLE_USER']);
//
////            $user->setAuthor($this->getReference(sprintf('auteur%d', $i)));
//        }


        $manager->flush();
    }
}
