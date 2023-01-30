<?php

namespace App\Controller;

use App\Form\FilmSearchFormType;
use App\Form\SerieSearchFormType;
use App\Repository\SeriesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/videos_series')]
class SerieController extends AbstractController
{
    #[Route('/videos_series', name: 'app_series')]
    public function series(): Response
    {
        return $this->render('videos_series/videos_series.html.twig');
    }

    #[Route('/serie/{id}', name: 'app_serie_show')]
    public function series_show(): Response
    {
        return $this->render('videos_series/serie_show.html.twig');
    }

    #[Route('/serie/new', name: 'app_serie_new')]
    public function serie_new(SeriesRepository $serieRepository): Response
    {
//        $serie = $serieRepository->find($id);
        $serie=[];
        return $this->render('videos_series/serie_show.html.twig', compact('serie'));
    }

    #[Route('/find', name: 'tools_find')]
    public function find()
    {
        $form = $this->createForm(SerieSearchFormType::class);
        $series = [];
//        dd($form);
        return $this->render('videos_series/recherche.html.twig', compact('form', 'series'));
    }

}
