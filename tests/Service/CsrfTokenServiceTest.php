<?php

namespace App\Tests\Service;

use App\Service\CsrfTokenService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class CsrfTokenServiceTest extends TestCase
{
    public function testGetToken(): void
    {
        $mockTokenId = 'test_token';
        $mockTokenValue = 'mocked_csrf_token';

        // Mock du CsrfTokenManagerInterface
        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManagerMock->method('getToken')
            ->with($mockTokenId)
            ->willReturn(new CsrfToken($mockTokenId, $mockTokenValue));

        $csrfTokenService = new CsrfTokenService($csrfTokenManagerMock);

        $this->assertSame($mockTokenValue, $csrfTokenService->getToken($mockTokenId));
    }

    public function testGetTokenWithDefaultId(): void
    {
        $defaultTokenId = 'default';
        $defaultTokenValue = 'default_mocked_csrf_token';

        $csrfTokenManagerMock = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfTokenManagerMock->method('getToken')
            ->with($defaultTokenId)
            ->willReturn(new CsrfToken($defaultTokenId, $defaultTokenValue));

        $csrfTokenService = new CsrfTokenService($csrfTokenManagerMock);

        $this->assertSame($defaultTokenValue, $csrfTokenService->getToken());
    }
}