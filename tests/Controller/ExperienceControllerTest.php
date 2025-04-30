<?php

namespace App\Tests\Controller;

use App\Controller\ExperienceController;
use App\Entity\Experience;
use App\Entity\User;
use App\Form\ExperienceType;
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

class ExperienceControllerTest extends TestCase
{
    public function testNewExperienceSuccess(): void
    {
        $user = $this->createMock(User::class);
        $experience = new Experience();

        $requestData = [
            '_csrf_token' => 'valid_token',
            'title' => 'Développeur Symfony',
            'description' => 'Développement backend en PHP',
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')->with($request);
        $form->expects($this->once())->method('submit')->with($requestData);
        $form->expects($this->once())->method('isValid')->willReturn(true);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $csrfManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfManager->method('isTokenValid')
            ->with(new CsrfToken('experience_form', 'valid_token'))
            ->willReturn(true);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($this->isInstanceOf(Experience::class));
        $entityManager->expects($this->once())->method('flush');

        $userProvider = $this->createMock(UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $controller = new ExperienceController();
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->new($request, $entityManager, $csrfManager, $userProvider);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testNewExperienceInvalidCsrf(): void
    {
        $user = $this->createMock(User::class);

        $requestData = [
            '_csrf_token' => 'invalid_token',
            'title' => 'Développeur',
            'description' => 'Description',
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')->with($request);
        $form->expects($this->once())->method('submit')->with($requestData);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $csrfManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfManager->method('isTokenValid')
            ->with(new CsrfToken('experience_form', 'invalid_token'))
            ->willReturn(false);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $userProvider = $this->createMock(UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $controller = new ExperienceController();
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->new($request, $entityManager, $csrfManager, $userProvider);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testEditExperienceSuccess(): void
    {
        $user = $this->createMock(User::class);
        $experience = new Experience();

        $requestData = [
            '_csrf_token' => 'valid_token',
            'title' => 'Lead Dev',
            'description' => 'Mise en place d’architecture',
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')->with($request);
        $form->expects($this->once())->method('submit')->with($requestData);
        $form->expects($this->once())->method('isValid')->willReturn(true);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $csrfManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfManager->method('isTokenValid')
            ->with(new CsrfToken('experience_form', 'valid_token'))
            ->willReturn(true);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($experience);
        $entityManager->expects($this->once())->method('flush');

        $userProvider = $this->createMock(UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $controller = new ExperienceController();
        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->edit($request, $experience, $entityManager, $csrfManager, $userProvider);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}