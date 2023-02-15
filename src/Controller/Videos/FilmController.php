<?php

namespace App\Controller\Videos;

use App\Form\FilmSearchFormType;
use App\Repository\FilmRepository;
use App\Service\FilmsServices;
use App\Service\TmbdServices;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/films')]
class FilmController extends AbstractController
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/index', name: 'app_films')]
    public function films(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $filmRepository->getAllFilms();
        $limit = 25;
        $page = $request->query->getInt('page', 1);
        if ($page === 0) $page = 1;
        $films = $paginator->paginate($query, $page, $limit);

        return $this->render('secteur_videos/videos_films/films_index.html.twig', compact('films'));
    }

    #[Route('/show/{id}', name: 'app_film_show')]
    public function films_show(FilmRepository $filmRepository, ?int $id): Response
    {
        $film = $filmRepository->find($id);
        return $this->render('secteur_videos/videos_films/film_show.html.twig', compact('film'));
    }

    #[Route('/new', name: 'app_film_new')]
    public function film_new(FilmsServices $filmsServices): Response
    {
        $data = [];
        $filmsServices->newFilm($data);
        $this->addFlash('success', 'Film ajouté');
        return $this->render('secteur_videos/videos_films/films_index.html.twig');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/findTMBD', name: 'app_film_find_tmbd')]
    public function find(TmbdServices $tmbdServices, Request $request): Response
    {
        $form = $this->createForm(FilmSearchFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $data = $tmbdServices->findData($entity['titre']);

            return $this->render('secteur_videos/videos_films/apercu.html.twig', compact('data'));
        }
            return $this->render('secteur_videos/videos_films/recherche.html.twig', compact('form'));
    }
}
