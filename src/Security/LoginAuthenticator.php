<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginAuthenticator extends AbstractAuthenticator
{

    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }
    public function supports(Request $request): ?bool
    {
        return $request->getPathInfo() === '/login' && $request->isMethod('POST');
    }

    public function authenticate(Request $request): Passport
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            throw new CustomUserMessageAuthenticationException('Invalid JSON data.');
        }

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;
        $csrfToken = $data['_csrf_token'] ?? null;

        if (!$username || !$password || !$csrfToken) {
            throw new CustomUserMessageAuthenticationException('Missing username, password, or CSRF token.');
        }

        $user = $this->getUserByUsername($username);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }

        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($password)
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new JsonResponse(['message' => 'Login successful']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['error' => $exception->getMessageKey()], Response::HTTP_UNAUTHORIZED);
    }
    private function getUserByUsername(
        string $username): ?\App\Entity\User
    {
        return $this->userRepository->findOneByEmail($username);
    }
}
