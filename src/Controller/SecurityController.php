<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        Security $security
    ): Response {
        if ($security->getUser() && $security->getUser()->isVerified()) {
            return $this->redirectToRoute('app_home');
        }
        if ($request->isMethod('GET')) {
            return $this->render('security/login.html.twig');
        }
        return new Response('Erreur lors de la connexion', Response::HTTP_BAD_REQUEST);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
