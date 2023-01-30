<?php

namespace App\Controller;

use App\Entity\Import;
use App\Form\ImportType;
use App\Repository\ImportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/import')]
class ImportController extends AbstractController
{
    #[Route('/', name: 'app_import_index', methods: ['GET'])]
    public function index(ImportRepository $importRepository): Response
    {
        return $this->render('import/index.html.twig', [
            'imports' => $importRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_import_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImportRepository $importRepository): Response
    {
        $import = new Import();
        $form = $this->createForm(ImportType::class, $import);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $importRepository->save($import, true);

            return $this->redirectToRoute('app_import_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('import/new.html.twig', [
            'import' => $import,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_import_show', methods: ['GET'])]
    public function show(Import $import): Response
    {
        return $this->render('import/show.html.twig', [
            'import' => $import,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_import_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Import $import, ImportRepository $importRepository): Response
    {
        $form = $this->createForm(ImportType::class, $import);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $importRepository->save($import, true);

            return $this->redirectToRoute('app_import_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('import/edit.html.twig', [
            'import' => $import,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_import_delete', methods: ['POST'])]
    public function delete(Request $request, Import $import, ImportRepository $importRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$import->getId(), $request->request->get('_token'))) {
            $importRepository->remove($import, true);
        }

        return $this->redirectToRoute('app_import_index', [], Response::HTTP_SEE_OTHER);
    }
}
