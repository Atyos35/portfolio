<?php

namespace App\Tests\Controller;

use App\Controller\UserController;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\SecurityBundle\Security;

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
            'user' => 1,
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
}