<?php
namespace App\Tests\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\LoginAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class LoginAuthenticatorTest extends TestCase
{
    private $userRepository;
    private $loginAuthenticator;
    private $mockUser;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->mockUser = $this->createMock(User::class);
        $this->mockUser->method('getEmail')->willReturn('user@example.com');

        $this->loginAuthenticator = new LoginAuthenticator($this->userRepository);
    }

    public function testSupports()
    {
        $request = Request::create('/login', 'POST');
        $this->assertTrue($this->loginAuthenticator->supports($request));

        $request = Request::create('/login', 'GET');
        $this->assertFalse($this->loginAuthenticator->supports($request));

        $request = Request::create('/other', 'POST');
        $this->assertFalse($this->loginAuthenticator->supports($request));
    }

    public function testAuthenticateWithValidData()
    {
        $this->userRepository->expects($this->once())
            ->method('findOneByEmail')
            ->with('user@example.com')
            ->willReturn($this->mockUser);

        $data = [
            'username' => 'user@example.com',
            'password' => 'password123',
            '_csrf_token' => 'valid_csrf_token'
        ];
        $request = Request::create('/login', 'POST', [], [], [], [], json_encode($data));

        $passport = $this->loginAuthenticator->authenticate($request);

        $this->assertInstanceOf(Passport::class, $passport);
        $this->assertCount(2, $passport->getBadges()); // UserBadge et CsrfTokenBadge
    }

    public function testAuthenticateWithMissingData()
    {
        $data = [
            'username' => 'user@example.com',
            '_csrf_token' => 'valid_csrf_token'
        ];
        $request = Request::create('/login', 'POST', [], [], [], [], json_encode($data));

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $this->expectExceptionMessage('Missing username, password, or CSRF token.');

        $this->loginAuthenticator->authenticate($request);
    }

    public function testAuthenticateWithInvalidCredentials()
    {
        $this->userRepository->expects($this->once())
            ->method('findOneByEmail')
            ->with('user@example.com')
            ->willReturn(null); // No user found

        $data = [
            'username' => 'user@example.com',
            'password' => 'wrongpassword',
            '_csrf_token' => 'valid_csrf_token'
        ];
        $request = Request::create('/login', 'POST', [], [], [], [], json_encode($data));

        $this->expectException(CustomUserMessageAuthenticationException::class);
        $this->expectExceptionMessage('Invalid credentials.');

        $this->loginAuthenticator->authenticate($request);
    }

    public function testOnAuthenticationSuccess()
    {
        $request = Request::create('/login', 'POST');
        $token = $this->createMock(TokenInterface::class);
        $firewallName = 'main';

        $response = $this->loginAuthenticator->onAuthenticationSuccess($request, $token, $firewallName);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(['message' => 'Login successful'], json_decode($response->getContent(), true));
    }

    public function testOnAuthenticationFailure()
    {
        $request = Request::create('/login', 'POST');
        $exception = new AuthenticationException('Invalid credentials');

        $response = $this->loginAuthenticator->onAuthenticationFailure($request, $exception);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(['error' => 'An authentication exception occurred.'], json_decode($response->getContent(), true));
    }
}