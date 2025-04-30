<?php

namespace App\Tests\Service;

use App\Entity\User;
use App\Provider\UserProvider;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProviderTest extends TestCase
{
    public function testGetCurrentUser(): void
    {
        $user = $this->createMock(User::class);
        $user->method('getId')->willReturn(1);
        $user->method('getEmail')->willReturn('testuser@example.com');

        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $userRepository = $this->createMock(\Doctrine\ORM\EntityRepository::class);
        
        $entityManager->method('getRepository')->willReturn($userRepository);
        $userRepository->method('find')->willReturn($user);
        
        $userProvider = new UserProvider($security, $entityManager);

        $result = $userProvider->getCurrentUser();
        
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(1, $result->getId());
        $this->assertEquals('testuser@example.com', $result->getEmail());
    }

    public function testGetCurrentUserWhenNotAuthenticated(): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn(null);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $userProvider = new UserProvider($security, $entityManager);

        $result = $userProvider->getCurrentUser();

        $this->assertNull($result);
    }
}
