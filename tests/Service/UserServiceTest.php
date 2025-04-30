<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Service\UserService;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testFormatUserData()
    {
        $user = new User();
        $user->setFirstname('John')
            ->setLastname('Doe')
            ->setEmail('john.doe@example.com')
            ->setJob('Developer')
            ->setLinkedin('https://www.linkedin.com/in/johndoe')
            ->setAge('30')
            ->setCity('Paris')
            ->setPhone('1234567890');

        $userService = new UserService();

        $result = $userService->formatUserData($user);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('firstname', $result);
        $this->assertArrayHasKey('lastname', $result);
        $this->assertArrayHasKey('email', $result);
        $this->assertArrayHasKey('job', $result);
        $this->assertArrayHasKey('linkedin', $result);
        $this->assertArrayHasKey('age', $result);
        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('phone', $result);

        $this->assertNull($result['id']);
        $this->assertEquals('John', $result['firstname']);
        $this->assertEquals('Doe', $result['lastname']);
        $this->assertEquals('john.doe@example.com', $result['email']);
        $this->assertEquals('Developer', $result['job']);
        $this->assertEquals('https://www.linkedin.com/in/johndoe', $result['linkedin']);
        $this->assertEquals('30', $result['age']);
        $this->assertEquals('Paris', $result['city']);
        $this->assertEquals('1234567890', $result['phone']);
    }
}