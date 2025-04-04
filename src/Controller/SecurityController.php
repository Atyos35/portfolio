<?php

namespace App\Controller;

use App\Service\CsrfTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(
        Request $request,
        CsrfTokenService $csrfTokenService,
        Security $security
    ): Response {
        if ($security->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($request->isMethod('GET')) {
            $csrfToken = $csrfTokenService->getToken('authenticate');
            return $this->render('security/login.html.twig', [
                'csrf_token' => $csrfToken,
            ]);
        }
        return new Response('Erreur lors de la connexion', Response::HTTP_BAD_REQUEST);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
