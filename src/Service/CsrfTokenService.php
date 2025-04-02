<?php

namespace App\Service;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTokenService
{
    private CsrfTokenManagerInterface $csrfTokenManager;

    public function __construct(CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function getToken(string $tokenId = 'default'): string
    {
        return $this->csrfTokenManager->getToken($tokenId)->getValue();
    }
}
