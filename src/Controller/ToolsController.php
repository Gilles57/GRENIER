<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Import;
use App\Form\FilmSearchFormType;
use App\Repository\FilmRepository;
use App\Repository\ImportRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


#[Route('/tools')]
class ToolsController extends AbstractController
{

    public function __construct(private HttpClientInterface $client,)
    {
    }

    //---------------------- FONCTIONS UTILITAIRES ------------------------------//

    public function findOneFilm(
        string $titre,
        string $annee,
    ): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/search/movie?api_key=c7924bfc3e4208e9e6eafb5beaee9940&query=' . $titre . '&language=fr'
        );
        $temp = json_decode($response->getContent(), true);
        $result = $temp['results'][0];

        if ($annee == substr($result['release_date'], 0, 4)) {
            $id = $result['id'];

            // À partir de l'id, on va chercher la fiche détaillée
            $response = $this->client->request(
                'GET',
                'https://api.themoviedb.org/3/movie/' . $id . '?api_key=c7924bfc3e4208e9e6eafb5beaee9940&language=fr'
            );
            $film = json_decode($response->getContent(), true);

            return $film;
        } else {
            return [];
        }
    }

    //---------------------- À VERIFIER ------------------------------//
    // takes URL of image and Path for the image as parameter
    public function download_image($url, $path)
    {
        $newfname = $path;
        $file = fopen($url, 'rb');
        if ($file) {
            $newf = fopen($newfname, 'wb');
            if ($newf) {
                while (!feof($file)) {
                    fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
                }
            }
        }
        if ($file) {
            fclose($file);
        }
        if ($newf) {
            fclose($newf);
        }
    }



    //------------------------ WORKFLOW CORRECT -----------------------//

    // Création du catalogue depuis Python
    #[Route('/catalogue', name: 'app_tools_create_catalogue')]
    public function catalogue(): RedirectResponse
    {
        $process = new Process(['python3', '/Users/gilles/Documents/INFORMATIQUE/CODES_SOURCES/Python/Outils/_catalogue_maxtor.py']);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->addFlash('danger', 'Il y a eu un problème');
            throw new ProcessFailedException($process);
        } else {
            $this->addFlash('success', 'Catalogue téléchargé');
            return $this->redirectToRoute('app_admin');
        }
    }

    // Chargement du catalogue csv dans Imports
    #[Route('/import', name: 'app_tools_import_csv')]
    public function importCsv(EntityManagerInterface $em): RedirectResponse
    {
        $reader = Reader::createFromPath('datas/Films.csv', 'r');
        $reader->setDelimiter('|');

        $records = $reader->getIterator();
        foreach ($records as $record) {
            $import = (new Import())
                ->setFichier($record[0])
                ->setTitle(trim($record[1], '"'))
                ->setExtension(trim($record[2], '"'))
                ->setLangue(trim($record[3], '"'))
                ->setAnnee(trim($record[4], '"'));
            $em->persist($import);
        }
        $em->flush();
        $this->addFlash('success', 'Les données ont été importées avec succès.');

        return $this->redirectToRoute('app_admin');
    }

    // Transfert des Imports dans Films
    #[Route('/load_films', name: 'app_tools_load_films')]
    public function load(ImportRepository $importRepository, FilmRepository $filmRepository)
    {
        $imports = $importRepository->findAll();

        foreach ($imports as $import) {

            dump($import);
            $result = $this->findOneFilm($import->getFichier(), $import->getAnnee());
            if ($result!=[]) {
                // On récupère l'affiche et on l'enregistre
                $url = 'https://image.tmdb.org/t/p/w500' . $result['poster_path'];
                $affiche = basename($url);
//                file_put_contents('uploads/affiches/' . $affiche, file_get_contents($url));
//                // On crée le film
//                $this->newFilm($result);
                //return ->render('test/choix.html.twig',compact('results'));
            } else {
                // TODO
//                dd('RIEN du tout');
                //return $this->render('test/index.html.twig',compact('affiche', 'id', 'film'));
            }
        }
        $this->addFlash('success', 'Transfert effectué');
    }


    public function newFilm(EntityManagerInterface $em, $filmFinded, Import $newsTitre): void
    {
        $film = new Film();
        $film->setTitre($filmFinded['title']);
        $film->setTitreOriginal($filmFinded['original_title']);
        $film->setReleaseDate(new \DateTimeImmutable($filmFinded['release_date']));
//        $film->setAnneeSortie(intval($newsTitre->getAnnee()));
//        $film->setExtension($newsTitre->getExtension());
        $film->setCodeTmbd($filmFinded['imdb_id'] ?? "");
        $film->setCoupDeCoeur('false');
        $film->setAGarder('false');
        $film->setVu('false');
        $film->setMedia($filmFinded['poster_path']);
//        $film->addVersion($import->getLangue());
        $film->addCategory($filmFinded['title']);
        foreach ($filmFinded['genres'] as $genre) {
            $film->addGenre($genre['name']);
        }
        foreach ($filmFinded['genres'] as $genre) {
            $film->addLangue($genre['original_language']);
        }
        $em->persist($film);
        $em->remove($newsTitre);
        $em->flush();
    }
}