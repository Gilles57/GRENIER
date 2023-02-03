<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Import;
use App\Service\TmbdServices;
use App\Repository\FilmRepository;
use App\Repository\ImportRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        $reader = Reader::createFromPath('data/Films.csv', 'r');
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

        $process = new Process(['python3', '/Users/gilles/Documents/INFORMATIQUE/CODES_SOURCES/Python/Outils/delete-catalogue-file.py']);
        $process->run();
        // executes after the command finishes
        if (!$process->isSuccessful()) {
            $this->addFlash('danger', 'Il y a eu un problème pendant la suppression du fichier');
            throw new ProcessFailedException($process);
        } else {
            $this->addFlash('success', 'Les données ont été importées avec succès et le fichier a été supprimé');
        }
        return $this->redirectToRoute('app_workflow');
    }

    // Transfert des Imports dans Films
    #[Route('/load_films', name: 'app_tools_load_films')]
    public function load(TmbdServices $get_data, ImportRepository $importRepository, FilmRepository $filmRepository)
    {
        $imports = $importRepository->findAll();

        foreach ($imports as $import) {

            $result = $get_data->findData($import->getFichier(), $import->getAnnee());
            if ($result != ['non !']) {
                // On récupère l'affiche et on l'enregistre
                $url = 'https://image.tmdb.org/t/p/w500' . $result['poster_path'];
//                dump($import->getFichier()." a été trouvé");
                $affiche = basename($url);
                file_put_contents('uploads/affiches/' . $affiche, file_get_contents($url));
//                // On crée le film
                $this->newFilm($result);
                //return ->render('test/choix.html.twig',compact('results'));
            } else {
                // TODO
                dump($import->getFichier()." n'a pas été trouvé");
//                dd('RIEN du tout');
                //return $this->render('test/index.html.twig',compact('affiche', 'id', 'film'));
            }
        }
        $this->addFlash('success', 'Transfert effectué');
        die();
    }



}