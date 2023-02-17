<?php

namespace App\Controller\Cruds;

use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/visite')]
class VisiteController extends AbstractController
{
    #[Route('/', name: 'app_admin_visite_index', methods: ['GET'])]
    public function index(VisiteRepository $visiteRepository): Response
    {
        return $this->render('/admin/cruds/visite/index.html.twig', [
            'visites' => $visiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_visite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VisiteRepository $visiteRepository): Response
    {
        $visite = new Visite();
        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les photos transmises par le formulaire
            $media = $form->get('media')->getData();
            // On crée un nom de fichier unique
            $fichier = md5(uniqid()) . '.' . $media->guessExtension();
            // On enregistre le fichier sur le DD
            $media->move(
                $this->getParameter('visites_directory'),
                $fichier
            );
            $visite->setMedia($fichier);


            $visiteRepository->save($visite, true);

            return $this->redirectToRoute('app_admin_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/admin/cruds/visite/new.html.twig', [
            'visite' => $visite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_visite_show', methods: ['GET'])]
    public function show(Visite $visite): Response
    {
        return $this->render('/admin/cruds/visite/show.html.twig', [
            'visite' => $visite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_visite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Visite $visite, VisiteRepository $visiteRepository): Response
    {
        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les photos transmises par le formulaire
            $media = $form->get('media')->getData();
            if ($media) {
                // On crée un nom de fichier unique
                $fichier = md5(uniqid()) . '.' . $media->guessExtension();
                // On enregistre le fichier sur le DD
                $media->move(
                    $this->getParameter('visites_directory'),
                    $fichier
                );
                $visite->setMedia($fichier);
            }
            $oeuvres = $form->get('oeuvre')->getData();
            foreach ($oeuvres as $oeuvre){
                $visite->addOeuvre($oeuvre);
            }
            $visiteRepository->save($visite, true);

            return $this->redirectToRoute('app_admin_visite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('/admin/cruds/visite/edit.html.twig', [
            'visite' => $visite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_visite_delete', methods: ['POST'])]
    public function delete(Request $request, Visite $visite, VisiteRepository $visiteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $visite->getId(), $request->request->get('_token'))) {
            $visiteRepository->remove($visite, true);
        }

        return $this->redirectToRoute('app_admin_visite_index', [], Response::HTTP_SEE_OTHER);
    }
}
