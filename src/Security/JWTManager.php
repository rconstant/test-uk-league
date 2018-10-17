<?php

namespace App\Security;

use Firebase\JWT\JWT;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTManager
{
    /**
     * @param UserInterface $user
     *
     * @return string
     */
    public function create(UserInterface $user)
    {
        $data = [
            'username'  => $user->getUsername(),
            'roles'     => $user->getRoles(),
            'iat'       => time(),
            'exp'       => time() + 60 * 60
        ];

        return JWT::encode($data, getenv('APP_SECRET'));
    }
}