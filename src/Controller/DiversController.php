<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DiversController extends AbstractController
{
    #[Route('/secteur_divers', name: 'app_divers')]
    public function index(): Response
    {
        return $this->render('secteur_divers/index.html.twig', [
            'controller_name' => 'DiversController',
        ]);
    }
}
