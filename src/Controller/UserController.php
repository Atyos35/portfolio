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

#[Route('/user')]
final class UserController extends AbstractController
{
    #[Route(name: 'app_user_get', methods: ['GET'])]
    public function get(Security $security): JsonResponse
    {
        /** @var User $user */
        $user = $security->getUser();

        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'job' => $user->getJob(),
            'linkedin' => $user->getLinkedin(),
            'age' => $user->getAge(),
            'city' => $user->getCity(),
            'phone' => $user->getPhone(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['POST'])]
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
        }

        return $this->json([
            'message' => 'Édition réussie',
            'user' => ['id' => $user->getId(), 'email' => $user->getEmail()]
        ], Response::HTTP_CREATED);
    }
}
