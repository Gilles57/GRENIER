<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ImportRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkflowController extends AbstractController
{
    // Affiche la page du workflow
    #[Route('/workflow', name: 'app_workflow')]
    public function index(ImportRepository $repo): Response
    {
        // Vérifie si le fichier Films.csv existe dans /public/data
        $nb_imports = $repo->count([]);
        $fichier_exist = false;

        $finder = new Finder();
        $finder->files()->in('/Users/gilles/Sites/S6/GRENIER/public/data');

        foreach ($finder as $file) {
            if($file->getFilename()=='Films.csv'){
                $fichier_exist = true;
            };
        }
        // Si oui, renvoie true et le nb de lignes, sinon, renvoie false et 0
        return $this->render('workflow/index.html.twig', compact('nb_imports', 'fichier_exist'));
    }
}
