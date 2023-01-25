<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
