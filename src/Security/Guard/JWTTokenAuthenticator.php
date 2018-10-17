<?php

namespace App\Security\Guard;

use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JWTTokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['invalid_grant' => 'Missing access token'], 401);
    }

    public function supports(Request $request)
    {
        return true;
    }

    public function getCredentials(Request $request)
    {
        return $request->headers->has('Authorization') ? $request->headers->get('Authorization') : false;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (empty($credentials)) {
            throw new AuthenticationException('Missing authorization header');
        }
        $parts = explode(' ', $credentials);
        if (count($parts) != 2) {
            throw new AuthenticationException('Invalid authorization header');
        }

        $tokenType = strtolower($parts[0]);
        $token = $parts[1];

        if ($tokenType == 'bearer') {
            try {
                $token = JWT::decode($token, getenv('APP_SECRET'), array('HS256'));
            } catch (\Exception $e) {
                throw new AuthenticationException($e->getMessage(), $e->getCode(), $e);
            }

            $user = $this->userProvider->loadUserByUsername($token->username);
            if (!$user instanceof User) {
                throw new AuthenticationException('User not found');
            }

            return $user;
        }

        throw new AuthenticationException('Unsupported token type in authorization header');
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['error' => 'authentication_failure', 'error_description' => $exception->getMessage()], 401);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }

}