<?php

namespace App\Controller;

use App\Service\CsrfTokenService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/csrf-token')]
final class CsrfTokenController extends AbstractController
{
    #[Route(name: 'app_block_index', methods: ['GET'])]
    public function getCsrfToken(CsrfTokenService $csrfTokenService): JsonResponse
    {
        return $this->json([
            'csrf_token' => $csrfTokenService->getToken('register_form'),
        ]);
    }
}
