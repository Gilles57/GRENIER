<?php

namespace App\Controller\Recettes;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    #[Route('/recettes', name: 'app_recettes')]
    public function index(): Response
    {
        return $this->render('secteur_recette/index.html.twig', [
            'controller_name' => 'RecetteController',
        ]);
    }
}
