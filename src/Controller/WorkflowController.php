<?php

namespace App\Controller;

use App\Entity\Import;
use App\Repository\ImportRepository;
use App\Service\FilmsServices;
use App\Service\TmbdServices;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/workflow')]
class WorkflowController extends AbstractController
{
    public function __construct(private HttpClientInterface $client,)
    {
    }

    //------------------------ ACCUEIL DU WORKFLOW -----------------------//
    // Affiche la page du workflow
    #[Route('/index', name: 'app_workflow')]
    public function index(ImportRepository $repo): Response
    {
        // Vérifie si le fichier Films.csv existe dans /public/data
        $nb_imports = $repo->count([]);
        $fichier_exist = false;

        $finder = new Finder();
        $finder->files()->in('/Users/gilles/Sites/S6/GRENIER/public/data');

        foreach ($finder as $file) {
            if ($file->getFilename() == 'Films.csv') {
                $fichier_exist = true;
            };
        }
        // Si oui, renvoie true et le nb de lignes, sinon, renvoie false et 0
        return $this->render('workflow/index.html.twig', compact('nb_imports', 'fichier_exist'));
    }

    //------------------------ WORKFLOW CORRECT -----------------------//

    // Création du fichier depuis Python
    #[Route('/create_fichier', name: 'app_tools_create_fichier')]
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
            return $this->redirectToRoute('app_workflow');
        }
    }

    // Chargement du fichier csv dans Imports

    /**
     * @throws InvalidArgument
     */
    #[Route('/Make_import', name: 'app_tools_make_import_csv')]
    public function importCsv(EntityManagerInterface $em): RedirectResponse
    {
        $reader = Reader::createFromPath('data/Films.csv', 'r');
        $reader->setDelimiter('|');

        $records = $reader->getIterator();
        $nbImports = 0;
        foreach ($records as $record) {
            if ($record[0] == 'fichier' && $record[1] == 'title' && $record[2] == 'extension' && $record[3] == 'langue') {
                continue;
            } else {
                $nbImports++;
                $import = (new Import())
                    ->setFichier($record[0])
                    ->setTitle(trim($record[1], '"'))
                    ->setExtension(trim($record[2], '"'))
                    ->setLangue(trim($record[3], '"'))
                    ->setAnnee(trim($record[4], '"'));
                $em->persist($import);
                if ($nbImports == 30) { // TODO à supprimer
                    break;
                }
            }
        }
        $em->flush();

        $process = new Process(['python3', '/Users/gilles/Documents/INFORMATIQUE/CODES_SOURCES/Python/Outils/delete-catalogue-file.py']);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->addFlash('danger', 'Il y a eu un problème pendant la suppression du fichier');
            throw new ProcessFailedException($process);
        } else {
            $this->addFlash('success', $nbImports . ' films ont été importés avec succès et le fichier a été supprimé');
        }
        return $this->redirectToRoute('app_workflow');
    }

    // Transfert automatique des Imports dans Films

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    #[Route('/transfert_auto_to_films', name: 'app_tools_transfert_auto_to_films')]
    public function loadAuto(
        FilmsServices          $filmsServices,
        TmbdServices           $tmbdServices,
        ImportRepository       $importRepository,
        EntityManagerInterface $em,
    ): RedirectResponse
    {
        $imports = $importRepository->findAll();
        foreach ($imports as $import) {
            $annee = $import->getAnnee();
            $titre = $import->getFichier();
            $datas = $tmbdServices->findData(preg_replace("/[^a-zA-Z0-9\s]/", "", $titre));

            foreach ($datas as $data) {
//                dump('-----PRINCIPALE-------');
//                dump($data);

                if ($annee && array_key_exists('release_date', $data)) {
                    // Si le titre et l'année correspondent, on traite le fichier
                    if ($annee == substr($data['release_date'], 0, 4)) {
                        $this->createFilm($data, $tmbdServices, $filmsServices, $em, $annee, $titre, $importRepository, $import);
                    }
                } else {
                    if (count($datas) == 1) {
                        $data = $datas[0];
                        // On crée le film et on supprime l'import
                        $this->createFilm($data, $tmbdServices, $filmsServices, $em, $annee, $titre, $importRepository, $import);
                    }
                }
            }
        }
        $em->flush();
        $this->addFlash('success', 'Transfert automatique effectué');
        return $this->redirectToRoute('app_workflow');
    }

    // Transfert manuel des Imports dans Films

    #[Route('/transfert_manuel_to_films', name: 'app_tools_transfert_manuel_to_films')]
    public function loadManuel(
        FilmsServices          $filmsServices,
        TmbdServices           $tmbdServices,
        ImportRepository       $importRepository,
        EntityManagerInterface $em,
    )
    {
        $imports = $importRepository->findAll();
        $results = [];
        $i = 0;
        foreach ($imports as $import) {
            $titre = $import->getFichier();
            $datas = $tmbdServices->findData(preg_replace("/[^a-zA-Z0-9\s]/", "", $titre));
            $results[$i]['import'] = $import;
            $results[$i]['datas'] = $datas;
            $i++;
        }
        return $this->render('workflow/choix.html.twig', compact('results'));
    }

    #[Route('/ajout_manuel/{dataId}/{importId}', name: 'app_tools_ajout_manuel')]
    public function ajoutManuel(int                    $dataId,
                                int                    $importId,
                                FilmsServices          $filmsServices,
                                TmbdServices           $tmbdServices,
                                ImportRepository       $importRepository,
                                EntityManagerInterface $em,)
    {
        $import = $importRepository->find($importId);
        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/movie/' . $dataId . '?api_key=c7924bfc3e4208e9e6eafb5beaee9940&language=fr'
        );
        $data = json_decode($response->getContent(), true);
//        dd($data);
        $annee = $import->getAnnee();
        $titre = $import->getFichier();
        $this->createFilm($data, $tmbdServices, $filmsServices, $em, $annee, $titre, $importRepository, $import);
        return $this->redirectToRoute('app_tools_transfert_manuel_to_films');

    }

    public function createFilm(mixed                  $data,
                               TmbdServices           $tmbdServices,
                               FilmsServices          $filmsServices,
                               EntityManagerInterface $em,
                               string                 $annee,
                               ?string                $titre,
                               ImportRepository       $importRepository,
                               Import                 $import): mixed
    {
        // Si l'affiche existe, on la récupère et on l'enregistre

        if (array_key_exists('poster_path', $data) && $data['poster_path'] != "") {
            $tmbdServices->uploadAffiche($data['poster_path']);
        } else {
            $data['poster_path'] = '/_Photo_non_disponible.webp';
        }
        // On crée le film
        $filmsServices->newFilm($em, $data, $titre, $annee);
        // On supprime l'import
        $importRepository->remove($import);
        $em->flush();
        return $data;
    }
}