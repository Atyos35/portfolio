<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

readonly class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface            $mailer,
        private EntityManagerInterface     $entityManager
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): Response
    {
        try {
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                $verifyEmailRouteName,
                (string) $user->getId(),
                (string) $user->getEmail(),
                ['id' => $user->getId()]
            );

            $context = $email->getContext();
            $context['signedUrl'] = $signatureComponents->getSignedUrl();
            $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
            $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

            $email->context($context);

            $this->mailer->send($email);

        } catch (\Exception $e) {
            return new Response('An error occurred while sending the email confirmation. Please try again.', Response::HTTP_BAD_REQUEST);
        }
        return new Response('OK', Response::HTTP_OK);
    }


    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, User $user): void
    {
        $this->verifyEmailHelper->validateEmailConfirmationFromRequest($request, (string) $user->getId(), (string) $user->getEmail());

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
