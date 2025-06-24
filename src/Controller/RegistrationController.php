<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Service\InitialDatasForUserService;

class RegistrationController extends AbstractController
{
    private InitialDatasForUserService $initialDatasForUserService;

    public function __construct(InitialDatasForUserService $initialDatasForUserService)
    {
        $this->initialDatasForUserService = $initialDatasForUserService;
    }

    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        if ($request->isMethod('GET')) {
            return $this->render('registration/register.html.twig');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $data = json_decode($request->getContent(), true);

        if($csrfTokenManager->isTokenValid(new CsrfToken('register', $data['_csrf_token']))){
            $form->submit($data);

            if (!$form->isValid()) {
                $errors = [];

                foreach ($form->getErrors(true) as $error) {
                    $errors[$error->getOrigin()->getName()] = $error->getMessage();
                }

                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $plainPassword = $form->get('plainPassword')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));
            $user->setRoles(["ROLE_USER"]);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->initialDatasForUserService->createInitialDatasForUser($user);

        }
        $this->addFlash('email', $user->getEmail());
        return $this->json([
            'message' => 'Inscription réussie',
            'user' => ['id' => $user->getId(), 'email' => $user->getEmail()]
        ], Response::HTTP_CREATED);
    }
}
