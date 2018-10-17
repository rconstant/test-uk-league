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

/**
 * Class JWTTokenAuthenticator
 * @package App\Security\Guard
 */
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

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new JsonResponse(['invalid_grant' => 'Missing access token'], 401);
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request)
    {
        return true;
    }

    /**
     * @param Request $request
     *
     * @return bool|mixed|null|string|string[]
     */
    public function getCredentials(Request $request)
    {
        return $request->headers->has('Authorization') ? $request->headers->get('Authorization') : false;
    }

    /**
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return null|UserInterface
     */
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

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return null|JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse(['error' => 'authentication_failure', 'error_description' => $exception->getMessage()], 401);
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }

}