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
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(#[CurrentUser] ?User $user): Response
    {
        return $this->render('admin/admin.html.twig', compact('user'));
    }

    #[Route('/create-admin', name: 'app_create_admin')]
    public function createAdmin(UserRepository $repo): Response
    {
        $u = new User();
        $u->setName('SALMON');
        $u->setForename('Gilles');
        $u->setEmail('g.salmon@free.fr');
        $u->setRoles(['USER_ADMIN']);

        // symfony console security:hash sellig
        $u->setPassword('$2y$13$cBJoq4FkvLi6tplVHJ8nDOqNMpEuW8KBqyOUGXWPW3VjhFz6.8V2C');

        $repo->save($u, flush: true);

        return $this->redirectToRoute('app_admin');
    }
}
