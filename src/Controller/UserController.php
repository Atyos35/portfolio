<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use App\Provider\UserProvider;

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['PUT', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        $form->submit($data);

        if($csrfTokenManager->isTokenValid(new CsrfToken('user_form', $data['_csrf_token']))) {
            if (!$form->isValid()) {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $entityManager->persist($user);
            $entityManager->flush();
        }else{
            return $this->json([
                'message' => 'Invalid csrf token',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'message' => 'Édition réussie',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'firstname' => $user->getFirstname(),
                'lastname' => $user->getLastname(),
                'job' => $user->getJob(),
                'linkedin' => $user->getLinkedin(),
                'city' => $user->getCity(),
                'phone' => $user->getPhone(),
                'age' => $user->getAge(),
                'profilePicture' => $user->getProfilePicture(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('/{id}/edit/profile_picture', name: 'app_user_edit_profile_picture', methods: ['POST'])]
    public function editProfilePicture(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager
    ): Response {
        $csrfToken = $request->request->get('_csrf_token');
        
        if (!$csrfTokenManager->isTokenValid(new CsrfToken('user_form', $csrfToken))) {
            return $this->json(['message' => 'Invalid CSRF token'], Response::HTTP_BAD_REQUEST);
        }

        $file = $request->files->get('profilePicture');

        if ($file && $file->isValid()) {
            $filename = uniqid() . '.' . $file->guessClientExtension();
            $file->move($this->getParameter('uploads_directory'), $filename);

            $user->setProfilePicture('/uploads/' . $filename);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->json([
                'message' => 'Photo mise à jour avec succès',
                'profilePicture' => $user->getProfilePicture(),
            ]);
        }

        return $this->json(['message' => 'Fichier invalide'], Response::HTTP_BAD_REQUEST);
    }
}
