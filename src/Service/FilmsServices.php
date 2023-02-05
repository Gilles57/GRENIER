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
    public function newFilm(EntityManagerInterface $em, $datas,$fichier, $annee): void
    {
        $film = new Film();
        $film->setFichierOriginal($fichier);
        $film->setDateOriginale($annee);
        $film->setTitre($datas['title']);
        $film->setTitreOriginal($datas['original_title']);
        $film->setReleaseDate(new \DateTimeImmutable($datas['release_date'] ?? ""));
        $film->setExtension("");
        $film->setCodeTmbd($datas['imdb_id'] ?? "");
        $film->setMedia($datas['poster_path']);
        $film->setSlogan($datas['tagline']);
        $film->setResume($datas['overview']);
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