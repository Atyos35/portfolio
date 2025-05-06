<?php

namespace App\Controller;

use App\Entity\Training;
use App\Service\TrainingService;
use App\Entity\User;
use App\Form\TrainingType;
use App\Repository\TrainingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Provider\UserProvider;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

#[Route('/training')]
final class TrainingController extends AbstractController
{
    #[Route('/new', name: 'app_training_new', methods: ['PUT', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider): Response
    {

        $training = new Training();

        $user = $userProvider->getCurrentUser();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);

        $form->submit($data);
        
        if($csrfTokenManager->isTokenValid(new CsrfToken('training_form', $data['_csrf_token']))) {
            if (!$form->isValid()) {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }
                
                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            $training->setUser($user);
            $entityManager->persist($training);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Création réussie',
        ], Response::HTTP_CREATED);
    }
}