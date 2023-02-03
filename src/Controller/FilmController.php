<?php

namespace App\Controller;

use App\Form\FilmSearchFormType;
use App\Repository\FilmRepository;
use App\Service\FilmsServices;
use App\Service\TmbdServices;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/')]
class FilmController extends AbstractController
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/films', name: 'app_films')]
    public function films(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $filmRepository->getAllFilms();
        $limit = 4;
        $page = $request->query->getInt('page', 1);
        if ($page === 0) $page = 1;
        $films = $paginator->paginate($query, $page, $limit);

        return $this->render('videos_films/films.html.twig', compact('films'));
    }


    #[Route('/film/{id}', name: 'app_film_show')]
    public function films_show(FilmRepository $filmRepository, ?int $id): Response
    {
        $film = $filmRepository->find($id);
        return $this->render('videos_films/film_show.html.twig', compact('film'));
    }

    #[Route('/film/new', name: 'app_film_new')]
    public function film_new(FilmsServices $filmsServices): Response
    {
        $data = [];
        $filmsServices->newFilm($data);
        $this->addFlash('success', 'Film ajouté');
        return $this->render('videos_films/films.html.twig');
    }

    #[Route('/findIMBD', name: 'tools_find_imbd')]
    public function find(TmbdServices $get_data, Request $request): Response
    {
        $form = $this->createForm(FilmSearchFormType::class);


        $form->handleRequest($request);
        $films=[];

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $data = $get_data->findData($entity['titre'], $entity['annee']);


            return $this->render('videos_films/apercu.html.twig', compact('data'));
        }

            return $this->render('videos_films/recherche.html.twig', compact('form', 'films'));
    }


}
