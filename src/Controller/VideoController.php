<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videos')]
class VideoController extends AbstractController
{
    #[Route('/', name: 'app_videos')]
    public function index(): Response
    {
        return $this->render('video/index.html.twig');
    }

    #[Route('/films', name: 'app_films')]
    public function films(FilmRepository $filmRepository, PaginatorInterface $paginator, Request $request): Response
    {
//        $films = $filmRepository->findAll();

        $query = $filmRepository->getAllFilms();
//        $limit = $this->getParameter('nb_posts_per_page');
        $limit = 4;
        $page = $request->query->getInt('page', 1);
        if ($page === 0) $page = 1;
        $films = $paginator->paginate($query, $page, $limit);


        return $this->render('video/films.html.twig', compact('films'));
    }

    #[Route('/series', name: 'app_series')]
    public function series(): Response
    {
        return $this->render('video/series.html.twig');
    }

    #[Route('/film/{id}', name: 'app_film_show')]
    public function films_show(FilmRepository $filmRepository, ?int $id): Response
    {
        $film = $filmRepository->find($id);
        return $this->render('video/film_show.html.twig', compact('film'));
    }

    #[Route('/serie/{id}', name: 'app_serie_show')]
    public function series_show(): Response
    {
        return $this->render('video/serie_show.html.twig');
    }

    #[Route('/film/new', name: 'app_film_new')]
    public function film_new(FilmRepository $filmRepository): Response
    {
//        $film = $filmRepository->find($id);
        $film=[];
        return $this->render('video/film_show.html.twig', compact('film'));
    }
}
