<?php

namespace App\Controller;

use App\Entity\Experience;
use App\Service\ExperienceService;
use App\Entity\User;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
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

#[Route('/experience')]
final class ExperienceController extends AbstractController
{
    #[Route('/new', name: 'app_experience_new', methods: ['PUT', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider): Response
    {

        $experience = new Experience();

        $user = $userProvider->getCurrentUser();

        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        $form->submit($data);
        
        if($csrfTokenManager->isTokenValid(new CsrfToken('experience_form', $data['_csrf_token']))) {
            if (!$form->isValid()) {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            $experience->setUser($user);
            $entityManager->persist($experience);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Création réussie',
            'experience' => [
                'id' => $experience->getId(),
                'name' => $experience->getName(),
                'start_date' => $experience->getStartDate(),
                'end_date' => $experience->getEndDate(),
                'description' => $experience->getDescription(),
                'duration' => $experience->getDuration(),
                'company' => $experience->getCompany(),
                'city' => $experience->getCity(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit', name: 'app_experience_edit', methods: ['PUT', 'POST'])]
    public function edit(
        Request $request, 
        Experience $experience, 
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider): Response
    {
        $user = $userProvider->getCurrentUser();

        $data = json_decode($request->getContent(), true);
        
        $form = $this->createForm(ExperienceType::class, $experience);
        $form->handleRequest($request);

        $form->submit($data);

        if($csrfTokenManager->isTokenValid(new CsrfToken('experience_form', $data['_csrf_token']))) {
            if (!$form->isValid()) {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
            
            $entityManager->persist($experience);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Édition réussie',
            'experience' => [
                'id' => $experience->getId(),
                'name' => $experience->getName(),
                'start_date' => $experience->getStartDate(),
                'end_date' => $experience->getEndDate(),
                'description' => $experience->getDescription(),
                'duration' => $experience->getDuration(),
                'company' => $experience->getCompany(),
                'city' => $experience->getCity(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_experience_delete', methods: ['DELETE'])]
    public function delete(
        Request $request,
        Experience $experience,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserProvider $userProvider): Response 
    {
        $token = $request->headers->get('X-CSRF-TOKEN');
        $data = json_decode($request->getContent(), true);

        if (!$csrfTokenManager->isTokenValid(new CsrfToken('experience_form', $data['_csrf_token']))) {
            return $this->json([
                'message' => 'Jeton CSRF invalide.',
            ], Response::HTTP_FORBIDDEN);
        }

        $user = $userProvider->getCurrentUser();
        if (count($user->getExperiences()) <= 1) {
            return $this->json([
                'message' => 'Impossible de supprimer la dernière expérience.',
            ], Response::HTTP_BAD_REQUEST);
        }
        
        $entityManager->remove($experience);
        $entityManager->flush();

        return $this->json([
            'message' => 'Suppression réussie',
        ], Response::HTTP_NO_CONTENT);
    }
}
