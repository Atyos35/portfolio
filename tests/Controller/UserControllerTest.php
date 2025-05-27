<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class UserControllerTest extends TestCase
{
    public function testEditUserSuccess(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);
        $user->method('getEmail')->willReturn('edited@example.com');
        $user->method('getFirstname')->willReturn('John');
        $user->method('getLastname')->willReturn('Doe');
        $user->method('getJob')->willReturn('Developer');
        $user->method('getLinkedin')->willReturn('https://linkedin.com/in/johndoe');
        $user->method('getCity')->willReturn('Paris');
        $user->method('getPhone')->willReturn('0123456789');
        $user->method('getAge')->willReturn('30');

        $requestData = [
            '_csrf_token' => 'valid_token',
            'email' => 'edited@example.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'job' => 'Developer',
            'linkedin' => 'https://linkedin.com/in/johndoe',
            'city' => 'Paris',
            'phone' => '0123456789',
            'age' => '30',
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

        $expectedJson = [
            'message' => 'Édition réussie',
            'user' => [
                'id' => 1,
                'email' => 'edited@example.com',
                'firstname' => 'John',
                'lastname' => 'Doe',
                'job' => 'Developer',
                'linkedin' => 'https://linkedin.com/in/johndoe',
                'city' => 'Paris',
                'phone' => '0123456789',
                'age' => '30',
            ]
        ];

        $this->assertJsonStringEqualsJsonString(json_encode($expectedJson), $response->getContent());
    }
}