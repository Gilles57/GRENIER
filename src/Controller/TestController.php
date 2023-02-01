<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use App\Repository\ImportRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TestController extends AbstractController
{
    public function __construct(private HttpClientInterface $client,)
    {
    }

    //---------------------- EN COURS ------------------------------//
    #[Route('/import_film/{titre}/{annee}', name: 'app_test')]
    public function findOneFilm(
        EntityManagerInterface $em,
        ImportRepository       $importRepository,
        FilmRepository         $filmRepository,
        string                 $titre,
        string                 $annee,
    ): Response
    {

        $response = $this->client->request(
            'GET',
            'https://api.themoviedb.org/3/search/movie?api_key=c7924bfc3e4208e9e6eafb5beaee9940&query=' . $titre . '&language=fr'
        );
        $temp = json_decode($response->getContent(), true);
        $result = $temp['results'][0];

        if ($annee == substr($result['release_date'], 0, 4)){
            $id = $result['id'];
            $response = $this->client->request(
                'GET',
                'https://api.themoviedb.org/3/movie/' . $id . '?api_key=c7924bfc3e4208e9e6eafb5beaee9940&language=fr'
            );
            $filmFinded = json_decode($response->getContent(), true);
            $affiche = $filmFinded['poster_path'];

            // Initialize a file URL to the variable
            $url = 'https://image.tmdb.org/t/p/w500/' . $affiche;

            // Use basename() function to return the base name of file
            $file_name = basename($url);
            if (file_put_contents('uploads/affiches/' . $file_name, file_get_contents($url))) {
//                $film = new Film();
//                $film->setTitre($filmFinded['title']);
//                $film->setTitreOriginal($filmFinded['original_title']);
//                $film->setReleaseDate(new \DateTimeImmutable($filmFinded['release_date']));
//                $film->setAnneeSortie(intval($newsTitre->getAnnee()));
//                $film->setExtension($newsTitre->getExtension());
//                $film->setCodeTmbd($filmFinded['imdb_id'] ?? "");
//                $film->setCoupDeCoeur('false');
//                $film->setAGarder('false');
//                $film->setVu('false');
//                $film->setMedia($filmFinded['poster_path']);
//            $film->addVersion($import->getLangue());
//            $film->addCategory($filmFinded['title']);
//                foreach ($filmFinded['genres'] as $genre) {
//                    $film->addGenre($genre['name']);
//                }
//                foreach ($filmFinded['genres'] as $genre) {
//                    $film->addLangue($genre['original_language']);
//                }
//                $em->persist($film);
//                $em->remove($newsTitre);
            }
//            dd($filmFinded);

            return $this->render('test/index.html.twig',compact('affiche', 'id', 'filmFinded'));
        }else{
            dump('non trouvé : \n');
            $results = $temp['results'];
            return $this->render('test/choix.html.twig',compact('results'));

        }



        $em->flush();
        $this->addFlash('success', 'Transfert effectué');


        return $this->render('test/index.html.twig', ['controller_name' => 'TestController',]);
    }
}
