<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Service\TokenService;
use App\Service\UserService;
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

/**
 * @Route("/api", name="api_")
 */
class AccountController extends AbstractController
{
    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/account/add-account', name: 'create_account', methods: 'POST')]
    public function createAccount(Request $request, EntityManagerInterface $em): JsonResponse{
        $content = $request->getContent();
        $jsonData = json_decode($content, true);

        $user = UserService::getUserFromToken($request, $em);

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
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */

    #[Route('/account/current-account', name: 'get_current_account')]
    public function getBalance(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $user->getCurrentAccount()]);

        return new JsonResponse(["balance" =>$account->getBalance(), "name" => $account->getName()], 200);
    }
}
