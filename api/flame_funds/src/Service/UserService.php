<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    public static function getUserFromToken(Request $request, EntityManagerInterface $em){
        $tokenExtractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $token = $tokenExtractor->extract($request);
        if (!$token) {
            return new JsonResponse(['message' => 'Token not found'], 401);
        }
        $tokenService = new TokenService($token);
        $email = $tokenService->getEmail();

        $userRepo = $em->getRepository(User::class);

        return $userRepo->findOneBy(["email" => $email]);
    }
}