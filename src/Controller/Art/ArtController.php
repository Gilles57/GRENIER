<?php

namespace App\Controller\Art;

use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArtController extends AbstractController
{
    #[Route('/art', name: 'app_art')]
    public function index(VisiteRepository $visiteRepository): Response
    {
        $visites = $visiteRepository->findBy([], ['date' => 'desc']);
        return $this->render('secteur_art/index.html.twig', compact('visites'));
    }
}
