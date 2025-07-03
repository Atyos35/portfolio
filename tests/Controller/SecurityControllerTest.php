<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityControllerTest extends WebTestCase
{
    public function testRedirectIfUserIsVerified(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $userRepository = $container->get('doctrine')->getRepository(User::class);

        $user = $userRepository->findOneBy(['email' => 'valerian.guemene@gmail.com']);

        $this->assertNotNull($user);

        $client->loginUser($user);

        $client->request('GET', '/login');

        $this->assertResponseRedirects('/');
    }

    public function testRenderLoginTemplateOnGet(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }

    public function testLogoutThrowsLogicException(): void
    {
        $this->expectException(\LogicException::class);

        $controller = new \App\Controller\SecurityController();
        $controller->logout();
    }
}