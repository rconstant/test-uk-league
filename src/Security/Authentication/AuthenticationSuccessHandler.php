<?php

namespace App\Security\Authentication;

use App\Security\JWTManager;
use App\Security\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var JWTManager
     */
    private $JWTManager;

    /**
     * AuthenticationSuccessHandler constructor.
     *
     * @param JWTManager $JWTManager
     */
    public function __construct(JWTManager $JWTManager)
    {
        $this->JWTManager = $JWTManager;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     *
     * @return JWTAuthenticationSuccessResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        return $this->handleAuthenticationSuccess($token->getUser());
    }

    /**
     * @param UserInterface $user
     * @param null          $jwt
     *
     * @return JWTAuthenticationSuccessResponse
     */
    public function handleAuthenticationSuccess(UserInterface $user, $jwt = null)
    {
        if (null === $jwt) {
            $jwt = $this->JWTManager->create($user);
        }
        $response = new JWTAuthenticationSuccessResponse($jwt);
        return $response;
    }
}