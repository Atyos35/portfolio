<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    public function __construct(
        private VerifyEmailHelperInterface $verifyEmailHelper,
        private MailerInterface $mailer,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        try {
            // Générer la signature pour le lien de confirmation
            $signatureComponents = $this->verifyEmailHelper->generateSignature(
                $verifyEmailRouteName,
                (string) $user->getId(),
                (string) $user->getEmail(),
                ['id' => $user->getId()]
            );

            // Préparer le contexte de l'email avec les données de signature
            $context = $email->getContext();
            $context['signedUrl'] = $signatureComponents->getSignedUrl();
            $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
            $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

            $email->context($context);

            // Envoyer l'e-mail
            $this->mailer->send($email);

        } catch (\Exception $e) {
            // Capture l'exception et logge l'erreur ou fais autre chose
            // Tu peux aussi ajouter un message personnalisé si tu veux informer l'utilisateur
            dd($e->getMessage());

            // Tu peux ajouter une gestion d'erreur ici, comme un message flash
            $this->addFlash('error', 'An error occurred while sending the email confirmation. Please try again.');
        }
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
