<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class RegistrationController extends AbstractController
{
    public function __construct(private readonly EmailVerifier $emailVerifier)
    {
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

        if($csrfTokenManager->isTokenValid(new CsrfToken('registration_form', $data['_csrf_token']))){
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

            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('valerian.guemene@gmail.com', 'CV Maker'))
                    ->to((string) $user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

        }
        $this->addFlash('email', $user->getEmail());
        return $this->json([
            'message' => 'Inscription réussie',
            'user' => ['id' => $user->getId(), 'email' => $user->getEmail()]
        ], Response::HTTP_CREATED);
    }

    #[Route('/confirmed-registration', name: 'app_confirmed_registration')]
    public function confirmedRegistration(Request $request): Response
    {
        return $this->render('registration/confirmed_registration.html.twig');
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_login');
    }
}
