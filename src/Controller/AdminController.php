<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [

        ]);
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
