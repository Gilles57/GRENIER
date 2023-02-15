<?php

namespace App\Controller\Videos;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/secteur_videos')]
class VideoController extends AbstractController
{
    #[Route('/', name: 'app_videos')]
    public function index(): Response
    {
        return $this->render('secteur_videos/index.html.twig');
    }
}
