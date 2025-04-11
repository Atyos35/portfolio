<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Test\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class UserControllerTest extends TestCase
{
    public function testGetReturnsUserDataWhenAuthenticated(): void
    {
        // Arrange: simulate a User object
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);
        $user->method('getFirstname')->willReturn('John');
        $user->method('getLastname')->willReturn('Doe');
        $user->method('getEmail')->willReturn('john.doe@example.com');
        $user->method('getJob')->willReturn('Developer');
        $user->method('getLinkedin')->willReturn('https://linkedin.com/in/johndoe');
        $user->method('getAge')->willReturn('30');
        $user->method('getCity')->willReturn('Paris');
        $user->method('getPhone')->willReturn('+33123456789');

        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $controller = new UserController();

        $response = $controller->get($security);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $expectedData = [
            'id' => 1,
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'john.doe@example.com',
            'job' => 'Developer',
            'linkedin' => 'https://linkedin.com/in/johndoe',
            'age' => '30',
            'city' => 'Paris',
            'phone' => '+33123456789',
        ];

        $this->assertJsonStringEqualsJsonString(
            json_encode($expectedData),
            $response->getContent()
        );
    }

    public function testGetReturnsUnauthorizedWhenUserIsNull(): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn(null);

        $controller = new UserController();

        $response = $controller->get($security);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(401, $response->getStatusCode());

        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Unauthorized']),
            $response->getContent()
        );
    }

    public function testEditUserSuccess(): void
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');

        $requestData = [
            '_csrf_token' => 'valid_token',
            'email' => 'john.doe@example.com',
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
            ->with(new CsrfToken('user_form', 'valid_token'))
            ->willReturn(true);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->once())->method('persist')->with($user);
        $entityManager->expects($this->once())->method('flush');

        $controller = new UserController();

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->edit($request, $user, $entityManager, $csrfManager);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Édition réussie', $data['message']);
        $this->assertEquals('john.doe@example.com', $data['user']['email']);
    }

    public function testEditUserInvalidCsrf(): void
    {
        $user = new User();
        $user->setEmail('john.doe@example.com');

        $requestData = [
            '_csrf_token' => 'invalid_token',
            'email' => 'john.doe@example.com',
        ];

        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $form = $this->createMock(FormInterface::class);
        $form->expects($this->once())->method('handleRequest')->with($request);
        $form->expects($this->once())->method('submit')->with($requestData);

        $formFactory = $this->createMock(FormFactoryInterface::class);
        $formFactory->method('create')->willReturn($form);

        $csrfManager = $this->createMock(CsrfTokenManagerInterface::class);
        $csrfManager->method('isTokenValid')
            ->with(new CsrfToken('user_form', 'invalid_token'))
            ->willReturn(false);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->never())->method('persist');
        $entityManager->expects($this->never())->method('flush');

        $controller = new UserController();

        $container = $this->createMock(ContainerInterface::class);
        $container->method('get')->with('form.factory')->willReturn($formFactory);
        $controller->setContainer($container);

        $response = $controller->edit($request, $user, $entityManager, $csrfManager);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }
}