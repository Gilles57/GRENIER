<?php

namespace App\Service;

use App\Entity\Film;
use App\Entity\Import;
use Doctrine\ORM\EntityManagerInterface;

class FilmsServices
{

    /**
     * @throws \Exception
     */
    public function newFilm(EntityManagerInterface $em, $datas, $fichier, $annee): void
    {
        $film = new Film();
        $film->setFichierOriginal($fichier);
        $film->setDateOriginale($annee);
        $film->setTitre($datas['title'] ?? 'TITRE MANQUANT');
        $film->setTitreOriginal($datas['original_title'] ?? 'TITRE ORIGINAL MANQUANT');
        $film->setReleaseDate($datas['release_date'] ? new \DateTimeImmutable($datas['release_date']) : null);
        $film->setExtension("");
        $film->setCodeTmbd($datas['imdb_id'] ?? null);
        $film->setMedia($datas['poster_path']);
        $film->setSlogan($datas['tagline'] ?? 'TAGLINE MANQUANTE');
        $film->setResume($datas['overview'] ?? 'OVERVIEW MANQUANT');
        $film->setCommentaires('');

//        $film->addVersion("");
//        foreach ($datas['genres'] as $genre) {
//            $film->addGenre($genre['name']);
//        }
//        foreach ($datas['langues'] as $langue) {
//            $film->addLangue($langue['spoken_languages']);
//        }
        $film->setCoupDeCoeur(false);
        $film->setAGarder(false);
        $film->setARemplacer(false);
        $film->setVu(false);

        $em->persist($film);
        $em->flush();
    }
}