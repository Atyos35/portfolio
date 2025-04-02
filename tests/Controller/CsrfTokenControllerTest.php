<?php

namespace App\Tests\Controller;

use App\Service\CsrfTokenService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CsrfTokenControllerTest extends WebTestCase
{
    public function testGetCsrfTokenWithValidRequest(): void
    {
        $client = static::createClient();
        $csrfTokenService = $this->createMock(CsrfTokenService::class);
        $csrfTokenService->method('getToken')->willReturn('mocked_csrf_token');

        $client->getContainer()->set(CsrfTokenService::class, $csrfTokenService);

        $client->request('POST', '/csrf-token', [], [], [
            'HTTP_Origin' => 'http://localhost:8000',
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['tokenId' => 'sample_token']));

        $this->assertResponseIsSuccessful();
        $this->assertJsonStringEqualsJsonString(json_encode(['csrf_token' => 'mocked_csrf_token']), $client->getResponse()->getContent());
    }

    public function testGetCsrfTokenWithInvalidOrigin(): void
    {
        $client = static::createClient();

        $client->request('POST', '/csrf-token', [], [], [
            'HTTP_Origin' => 'http://malicious-site.com',
            'CONTENT_TYPE' => 'application/json'
        ], json_encode(['tokenId' => 'sample_token']));

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'Accès interdit']), $client->getResponse()->getContent());
    }

    public function testGetCsrfTokenWithMissingTokenId(): void
    {
        $client = static::createClient();
        $client->request('POST', '/csrf-token', [], [], [
            'HTTP_Origin' => 'http://localhost:8000',
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([]));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertJsonStringEqualsJsonString(json_encode(['error' => 'Token ID manquant']), $client->getResponse()->getContent());
    }
}
