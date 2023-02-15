<?php

namespace App\Controller\Informatique;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfoController extends AbstractController
{
    #[Route('/informatique', name: 'app_info')]
    public function index(): Response
    {
        return $this->render('secteur_informatique/index.html.twig', [
            'controller_name' => 'InfoController',
        ]);
    }
}
