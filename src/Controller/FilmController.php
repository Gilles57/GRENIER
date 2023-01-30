<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Import;
use App\Form\FilmSearchFormType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/videos_films')]
class FilmController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/', name: 'app_films')]
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
    public function film_new(FilmRepository $filmRepository): Response
    {
//        $film = $filmRepository->find($id);
        $film = [];
        return $this->render('videos_films/film_show.html.twig', compact('film'));
    }

    #[Route('/find/{titre}', name: 'tools_find')]
    public function find(FilmRepository $filmRepository, ?string $titre): Response
    {
        $form = $this->createForm(FilmSearchFormType::class);
        $films = [];
        $film = $filmRepository->find($titre);
//        dd($form);
        return $this->render('videos_films/recherche.html.twig', compact('form', 'films'));
    }


}
