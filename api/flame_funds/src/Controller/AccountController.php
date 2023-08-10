<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Service\TokenService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\TokenExtractor\AuthorizationHeaderTokenExtractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;


class AccountController extends AbstractController
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    #[Route('/account', name: 'app_account')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AccountController.php',
        ]);
    }

    /**
     * @throws NotSupported
     * @throws ORMException
     * @throws \Exception
     */
    #[Route('/account/create', name: 'create_account')]
    public function createAccount(Request $request, EntityManagerInterface $em): JsonResponse{
        $content = $request->getContent();
        $jsonData = json_decode($content, true);

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneBy(["username" => $jsonData['username']]);


        if(!$user){
            return new JsonResponse("User does not exist", 500);
        }
        $newAccount = new Account();
        $newAccount->setUser($user);
        $newAccount->setBalance($jsonData['balance']);
        $currentDateTime = new DateTime();
        $newAccount->setCreatedDate($currentDateTime);
        $newAccount->setName($jsonData['name']);
        $newAccount->setIsDeleted(false);

        $em->persist($newAccount);
        $em->flush();

        return new JsonResponse("Success");
    }

    /**
     * @throws JWTDecodeFailureException
     */
    #[Route('/account/balance', name: 'get_balance')]
    public function getBalance(Request $request, EntityManagerInterface $em){
        $tokenExtractor = new AuthorizationHeaderTokenExtractor('Bearer', 'Authorization');
        $token = $tokenExtractor->extract($request);

        if (!$token) {
            return $this->json(['message' => 'Token not found'], 401);
        }

        $tokenService = new TokenService($token);
        dd($tokenService->getUsername());

    }
}
