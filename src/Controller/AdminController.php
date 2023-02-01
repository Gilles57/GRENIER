<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\ImportRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/', name: 'app_admin')]
    public function index(ImportRepository $repo): Response
    {
        $nb_imports = $repo->count([]);
        $fichier_exist = false;

        $finder = new Finder();
        $finder->files()->in('/Users/gilles/Sites/S6/GRENIER/public/data');

        foreach ($finder as $file) {
            if($file->getFilename()=='Films.csv'){
                $fichier_exist = true;
            };
        }
        return $this->render('admin/index.html.twig', compact('nb_imports', 'fichier_exist'));
    }

    #[Route('/data', name: 'app_data')]
    public function data(UsersRepository $repo): Response
    {
        $u = new Users();
        $u->setName('SALMON');
        $u->setForename('Gilles');
        $u->setEmail('g.salmon@free.fr');

//        symfony console security:hash sellig
        $u->setPassword('$2y$13$cBJoq4FkvLi6tplVHJ8nDOqNMpEuW8KBqyOUGXWPW3VjhFz6.8V2C');

        $repo->save($u, flush: true);

        return $this->redirectToRoute('app_admin');
    }
}
