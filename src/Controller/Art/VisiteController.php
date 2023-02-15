<?php

namespace App\Controller\Art;

use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/visite')]
class VisiteController extends AbstractController
{
    #[Route('/{?id}', name: 'app_visite_index', methods: ['GET'])]
    public function index(?int $id, VisiteRepository $visiteRepository): Response
    {
        $visite = $visiteRepository->find($id=1);
        return $this->render('/secteur_art/visite.html.twig', compact('visite'));
    }



    #[Route('/show/{id}', name: 'app_visite_show', methods: ['GET'])]
    public function show(Visite $visite): Response
    {
        return $this->render('secteur_art/visite.html.twig', [
            'visite' => $visite,
        ]);
    }



}
