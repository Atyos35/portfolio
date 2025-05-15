<?php

namespace App\Tests\Controller;

use App\Controller\BlockController;
use App\Entity\Block;
use App\Entity\User;
use App\Form\BlockType;
use App\Provider\UserProvider;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\DependencyInjection\Container;

class BlockControllerTest extends TestCase
{
    public function testNewBlockSuccess(): void
    {
        $user = $this->createMock(User::class);
        $block = new Block();

        $requestData = [
            '_csrf_token' => 'valid_token',
            'title' => 'Langages',
            'names' => [
                ['value' => 'PHP'],
                ['value' => 'JavaScript'],
                ['value' => 'Python']
            ],
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $expectedSubmitData = [
            '_csrf_token' => 'valid_token',
            'title' => 'Langages',
            'names' => ['PHP', 'JavaScript', 'Python']
        ];

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')->with($request);
        $form->expects($this->once())->method('submit')->with($expectedSubmitData);
        $form->expects($this->once())->method('isValid')->willReturn(true);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $csrfManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfManager->method('isTokenValid')
            ->with(new CsrfToken('block_form', 'valid_token'))
            ->willReturn(true);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Block::class));
        $entityManager->expects($this->once())->method('flush');

        $userProvider = $this->createMock(UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $controller = new BlockController();
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->new($request, $entityManager, $csrfManager, $userProvider);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testNewBlockInvalidCsrf(): void
    {
        $user = $this->createMock(User::class);

        $requestData = [
            '_csrf_token' => 'invalid_token',
            'title' => 'frameworks',
            'names' => [
                ['value' => 'Symfony'],
                ['value' => 'API Platform'],
                ['value' => 'NestJS'],
            ],
        ];

        $expectedSubmittedData = $requestData;
        $expectedSubmittedData['names'] = ['Symfony', 'API Platform', 'NestJS'];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')->with($request);
        $form->expects($this->once())->method('submit')->with($expectedSubmittedData);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $csrfManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfManager->method('isTokenValid')
            ->with(new CsrfToken('block_form', 'invalid_token'))
            ->willReturn(false);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $userProvider = $this->createMock(UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $controller = new BlockController();
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->new($request, $entityManager, $csrfManager, $userProvider);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testEditBlockSuccess(): void
    {
        $user = $this->createMock(User::class);
        $block = new Block();

        $requestData = [
            '_csrf_token' => 'valid_token',
            'title' => 'frameworks',
            'names' => [
                ['value' => 'Symfony'],
                ['value' => 'API Platform'],
                ['value' => 'NestJS'],
            ],
        ];

        $expectedSubmittedData = $requestData;
        $expectedSubmittedData['names'] = ['Symfony', 'API Platform', 'NestJS'];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')->with($request);
        $form->expects($this->once())->method('submit')->with($expectedSubmittedData);
        $form->expects($this->once())->method('isValid')->willReturn(true);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $csrfManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfManager->method('isTokenValid')
            ->with(new CsrfToken('block_form', 'valid_token'))
            ->willReturn(true);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($block);
        $entityManager->expects($this->once())->method('flush');

        $userProvider = $this->createMock(UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $controller = new BlockController();
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->edit($request, $block, $entityManager, $csrfManager, $userProvider);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}