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
    public function newFilm(EntityManagerInterface $em, $datas, Import $newsTitre): void
    {
        $film = new Film();
        $film->setTitre($datas['title']);
        $film->setTitreOriginal($datas['original_title']);
        $film->setReleaseDate(new \DateTimeImmutable($datas['release_date']));
        $film->setExtension($datas['extension']);
        $film->setCodeTmbd($datas['imdb_id'] ?? "");
        $film->setMedia($datas['poster_path']);
        $film->setCommentaires('');
        $film->addVersion($datas['version']);

        foreach ($datas['genres'] as $genre) {
            $film->addGenre($genre['name']);
        }
        foreach ($datas['genres'] as $genre) {
            $film->addLangue($genre['original_language']);
        }
        $film->setCoupDeCoeur('false');
        $film->setAGarder('false');
        $film->setARemplacer('false');
        $film->setVu('false');

        $em->persist($film);
        $em->remove($newsTitre);
        $em->flush();
    }
}