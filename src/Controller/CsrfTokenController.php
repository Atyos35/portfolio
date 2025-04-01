<?php

namespace App\Controller;

use App\Service\CsrfTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/csrf-token')]
final class CsrfTokenController extends AbstractController
{
    #[Route(name: 'app_get_csrf_token', methods: ['POST'])]
    public function getCsrfToken(
        Request $request,
        CsrfTokenService $csrfTokenService): JsonResponse
    {
        $origin = $request->headers->get('Origin');
        $allowedOrigin = 'http://localhost:8000';

        if ($origin !== $allowedOrigin) {
            return $this->json(['error' => 'Accès interdit'], Response::HTTP_FORBIDDEN);
        }

        $data = json_decode($request->getContent(), true);
        $tokenId = $data['tokenId'] ?? null;

        if (!$tokenId) {
            return $this->json(['error' => 'Token ID manquant'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'csrf_token' => $csrfTokenService->getToken($tokenId),
        ]);
    }
}
