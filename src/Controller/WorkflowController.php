<?php

namespace App\Controller;

use App\Entity\Import;
use App\Repository\ImportRepository;
use App\Service\FilmsServices;
use App\Service\TmbdServices;
use Doctrine\ORM\EntityManagerInterface;
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

#[Route('/workflow')]
class WorkflowController extends AbstractController
{
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
            $this->addFlash('success', $nbImports . 'films ont été importés avec succès et le fichier a été supprimé');
        }
        return $this->redirectToRoute('app_workflow');
    }

    // Transfert des Imports dans Films

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/transfert_to_films', name: 'app_tools_transfert_to_films')]
    public function load(
        FilmsServices          $filmsServices,
        TmbdServices           $tmbdServices,
        ImportRepository       $importRepository,
        EntityManagerInterface $em,
    )
    {
        $imports = $importRepository->findAll();

        foreach ($imports as $import) {

            $data = $tmbdServices->findData($import->getFichier(), $import->getAnnee());
            if ($data != ['non !']) {
                // On récupère l'affiche et on l'enregistre
                if ($data['poster_path']) {
                    $url = 'https://image.tmdb.org/t/p/w500' . $data['poster_path'];
                    $affiche = basename($url);
                    file_put_contents('uploads/affiches/' . $affiche, file_get_contents($url));
                } else {
                    $data['poster_path'] = '/_Photo_non_disponible.webp';
                }
                // On crée le film
                $filmsServices->newFilm($em, $data, $import->getFichier(), $import->getAnnee());
                // On supprime l'import
                $importRepository->remove($import);
            } else {
                // TODO
                dump($import->getFichier() . " n'a pas été trouvé");
//                dd('RIEN du tout');
            }
        }
        $em->flush();
        $this->addFlash('success', 'Transfert effectué');
    }

}
