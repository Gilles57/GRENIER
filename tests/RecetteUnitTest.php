<?php

namespace App\Tests;

use App\Entity\Recette;
use PHPUnit\Framework\TestCase;

class RecetteUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $recette = new Recette();
        $date = new \DateTimeImmutable('now');

        $recette->setTitre('Titre')
            ->setCreatedAt($date)
            ->setUpdatedAt($date)
            ->setIllustration('media')
            ->setdureePreparation('60 minutes');

        $this->assertTrue('Titre' === $recette->getTitre());
        $this->assertTrue($recette->getCreatedAt() === $date);
        $this->assertTrue($recette->getUpdatedAt() === $date);
        $this->assertTrue('media' === $recette->getIllustration());
        $this->assertTrue('60 minutes' === $recette->getdureePreparation());
    }

    public function testIsFalse(): void
    {
        $recette = new Recette();
        $date = new \DateTimeImmutable('now');

        $recette->setTitre('Titre')
            ->setCreatedAt($date)
            ->setUpdatedAt($date)
            ->setIllustration('media')
            ->setdureePreparation('60 minutes');

        $this->assertFalse('vide' === $recette->getTitre());
        $this->assertFalse($recette->getCreatedAt() === new \DateTimeImmutable('now'));
        $this->assertFalse($recette->getUpdatedAt() === new \DateTimeImmutable('now'));
        $this->assertFalse('vide' === $recette->getIllustration());
        $this->assertFalse('vide' === $recette->getdureePreparation());
    }

    public function testIsEmpty(): void
    {
        $recette = new Recette();

        $this->assertEmpty($recette->getTitre());
        $this->assertEmpty($recette->getCreatedAt());
        $this->assertEmpty($recette->getUpdatedAt());
        $this->assertEmpty($recette->getIllustration());
        $this->assertEmpty($recette->getdureePreparation());
    }

//    public function testNewRecette(): void
//    {
//
//        $recette = new Recette();
//
//
//        $recette->setTitre('Recette de test');
//
//                $this->recetteRepository->add($recette, true);
//
//                $verif = $this->recetteRepository->findByTitre('Recette de test');
//
//        $this->assertNotEmpty($verif);
//    }
}
