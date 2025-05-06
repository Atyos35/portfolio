<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Provider\UserProvider;
use App\Service\ExperienceService;
use App\Service\TrainingService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends WebTestCase
{
    public function testHomePage(): void
    {
        $client = static::createClient();
        $container = self::getContainer();
        
        $user = $container->get('doctrine')
                          ->getRepository(User::class)
                          ->findOneByEmail('valerian.guemene@gmail.com');

        $this->assertNotNull($user, 'L\'utilisateur n\'a pas été trouvé en base. Assure-toi que les fixtures sont chargées.');

        $client->loginUser($user);

        $userProvider = $this->createMock(UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $experienceService = $this->createMock(ExperienceService::class);
        $experienceService->method('getUserExperiences')->with($user)->willReturn(['exp1', 'exp2']);

        $trainingService = $this->createMock(TrainingService::class);
        $trainingService->method('getUserTrainings')->with($user)->willReturn(['train1', 'train2']);

        $userService = new UserService();

        $container->set(UserProvider::class, $userProvider);
        $container->set(ExperienceService::class, $experienceService);
        $container->set(TrainingService::class, $trainingService);
        $container->set(UserService::class, $userService);

        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}