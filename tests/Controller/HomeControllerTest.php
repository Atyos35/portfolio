<?php

use PHPUnit\Framework\TestCase;

class HomeControllerTest extends TestCase
{
    public function testHomeReturnsResponseWithExpectedData()
    {
        $user = $this->createMock(\App\Entity\User::class);

        $userProvider = $this->createMock(\App\Provider\UserProvider::class);
        $userProvider->method('getCurrentUser')->willReturn($user);

        $experienceService = $this->createMock(\App\Service\ExperienceService::class);
        $experienceService->method('getUserExperiences')->willReturn(['exp1', 'exp2']);

        $trainingService = $this->createMock(\App\Service\TrainingService::class);
        $trainingService->method('getUserTrainings')->willReturn(['training1']);

        $blockService = $this->createMock(\App\Service\BlockService::class);
        $blockService->method('getUserBlocks')->willReturn(['block1', 'block2', 'block3']);

        $userService = $this->createMock(\App\Service\UserService::class);
        $userService->method('formatUserData')->willReturn(['formatted' => 'user data']);

        $entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $security = $this->createMock(\Symfony\Bundle\SecurityBundle\Security::class);

        $controller = $this->getMockBuilder(\App\Controller\HomeController::class)
            ->onlyMethods(['render'])
            ->getMock();
            
        $controller->method('render')->willReturn(new \Symfony\Component\HttpFoundation\Response('rendered content'));

        $response = $controller->home(
            $security,
            $entityManager,
            $userProvider,
            $experienceService,
            $trainingService,
            $blockService,
            $userService
        );

        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\Response::class, $response);
        $this->assertEquals('rendered content', $response->getContent());
    }
}