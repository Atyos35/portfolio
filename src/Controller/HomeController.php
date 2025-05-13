<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Provider\UserProvider;
use App\Service\UserService;
use App\Service\ExperienceService;
use App\Service\TrainingService;
use App\Service\BlockService;

class HomeController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('/', name: 'app_home')]
    public function home(Security $security, 
        EntityManagerInterface $entityManager, 
        UserProvider $userProvider,
        ExperienceService $experienceService,
        TrainingService $trainingService,
        BlockService $blockService,
        UserService $userService): Response
    {
        $user = $userProvider->getCurrentUser();
        $experiences = $experienceService->getUserExperiences($user);
        $trainings = $trainingService->getUserTrainings($user);
        $blocks = $blockService->getUserBlocks($user);
        
        return $this->render('home/home.html.twig', [
            'user' => $userService->formatUserData($user),
            'experiences' => $experiences,
            'trainings' => $trainings,
            'blocks' => $blocks,
        ]);
    }

}