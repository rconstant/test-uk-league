<?php
namespace App\Security\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class JWTAuthenticationSuccessResponse
 * @package App\Security\Response
 */
class JWTAuthenticationSuccessResponse extends JsonResponse
{
    /**
     * JWTAuthenticationSuccessResponse constructor.
     *
     * @param       $token
     * @param array $data
     */
    public function __construct($token, array $data = [])
    {
        parent::__construct(['token' => $token] + $data);
    }
}