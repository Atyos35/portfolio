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

    #[Route('/{id}/edit', name: 'app_training_edit', methods: ['PUT', 'POST'])]
    public function edit(
        Request $request, 
        Training $training, 
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider): Response
    {
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
            
            $entityManager->persist($training);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Édition réussie',
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_training_delete', methods: ['DELETE'])]
    public function delete(
        Request $request,
        Training $training,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager): Response 
    {
        $token = $request->headers->get('X-CSRF-TOKEN');
        $data = json_decode($request->getContent(), true);

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('training_form', $data['_csrf_token']))) {
            return $this->json([
                'message' => 'Jeton CSRF invalide.',
            ], Response::HTTP_FORBIDDEN);
        }
        
        $entityManager->remove($training);
        $entityManager->flush();

        return $this->json([
            'message' => 'Suppression réussie',
        ], Response::HTTP_NO_CONTENT);
    }
}