<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TokenService
{
    private $token;
    private $tokenHeader;
    private $tokenPayload;

    public function __construct(String $token)
    {
        $this->token = $token;
        $tokenParts = explode(".", $this->token);
        $this->tokenHeader = base64_decode($tokenParts[0]);
        $this->tokenPayload = base64_decode($tokenParts[1]);
    }

    /**
     * @return String
     */
    public function getEmail(): String
    {
        $jwtPayload = json_decode($this->tokenPayload, true);

        return $jwtPayload['email'];
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return object|JsonResponse|null
     */

}