<?php

namespace App\Service;

use App\Entity\Film;
use App\Entity\Genre;
use App\Entity\Import;
use App\Entity\Langue;
use App\Entity\Version;
use App\Repository\GenreRepository;
use App\Repository\LangueRepository;
use App\Repository\VersionRepository;
use Doctrine\ORM\EntityManagerInterface;

class FilmsServices
{
    public function __construct(private readonly VersionRepository $versionRepository,
                                private readonly LangueRepository  $langueRepository,
                                private readonly GenreRepository   $genreRepository,
    )
    {
    }

    /**
     * @throws \Exception
     */
    public function newFilm(EntityManagerInterface $em,
                            Import                 $import,
                                                   $datas,
    ): void
    {
        $film = new Film();
        $film->setFichierOriginal($import->getFichier());
        $film->setDateOriginale($import->getAnnee());
        $film->setExtension($import->getExtension());

        $film->setTitre($datas['title'] ?? 'TITRE MANQUANT');
        $film->setTitreOriginal($datas['original_title'] ?? 'TITRE ORIGINAL MANQUANT');
        $film->setMedia($datas['poster_path']);
        $film->setCodeTmbd($datas['imdb_id'] ?? null);
        $film->setReleaseDate($datas['release_date'] ? new \DateTimeImmutable($datas['release_date']) : null);
        $film->setCommentaires('');
        $film->setFranchise($datas['collection'] ?? null);
        $film->setSlogan($datas['tagline'] ?? 'TAGLINE MANQUANTE');
        $film->setResume($datas['overview'] ?? 'OVERVIEW MANQUANT');

        $film->setCoupDeCoeur(false);
        $film->setAGarder(false);
        $film->setARemplacer(false);
        $film->setVu(false);

        $version = $this->versionRepository->findOneBy(['name' => $import->getVersion()]);
        if ($version) {
            $film->addVersion($version);
        } else {
            $newVersion = new Version();
            $newVersion->setName($import->getVersion());
            $film->addVersion($newVersion);
            $em->persist($newVersion);
        }


        foreach ($datas['genres'] as $genre) {
            $genreFound = $this->genreRepository->findOneBy(['name' => $genre['name']]);
            if ($genreFound) {
                $film->addGenre($genreFound);
            } else {
                $newGenre = new Genre();
                $newGenre->setName($genre['name']);
                $film->addGenre($newGenre);
                $em->persist($newGenre);
            }
        }


        foreach ($datas['spoken_languages'] as $langue) {
            $langueFound = $this->langueRepository->findOneBy(['name' => $langue['name']]);
            if ($langueFound) {
                $film->addLangue($langueFound);
            } else {
                $newLangue = new Langue();
                $newLangue->setName($langue['name']);
                $film->addLangue($newLangue);
                $em->persist($newLangue);
            }
        }


        $em->persist($film);
        $em->flush();
    }
}