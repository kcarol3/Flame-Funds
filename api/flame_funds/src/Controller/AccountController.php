<?php

namespace App\Controller;

use App\Entity\Account;
use App\Service\AccountService;
use App\Service\UserService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class AccountController extends AbstractController
{

    #[Route('/account/get-accounts', name: 'get_accounts', methods: 'GET')]
    public function getAccounts(EntityManagerInterface $em): JsonResponse
    {
        $accountRepository = $em->getRepository(Account::class);

        $accounts = $accountRepository->findAll();

        $dataToReturn = [];
        foreach ($accounts as $key => $acc){
            if(!$acc->isIsDeleted()){
                $oneRow = [];
                $oneRow["name"] = $acc->getName();
                $dataToReturn[] = $oneRow;
            }
        }

        if($dataToReturn){
            return new JsonResponse($dataToReturn, 200);
        } else {
            return new JsonResponse(null, 400);
        }

    }

    /**
     * Dodanie nowego do użytkownika
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
    #[Route('/account/all-accounts', name: 'get_all_accounts', methods: "GET")]
    public function getAllAccounts(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);
        $accounts = $user->getAccounts();
        $dataToReturn = [];

        foreach ($accounts as $account){
            if(!$account->isIsDeleted()){
                $dataToReturn[] = [
                    "id" => $account->getId(),
                  "name" => $account->getName(),
                  "balance" => $account->getBalance()
                ];
            }
        }

        return new JsonResponse($dataToReturn, 200);
    }

    /**
     * Zmiana aktualnego konta użytkownika na wybrane w formularzu.
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route("/account/change-account", name: 'change_account', methods: "PUT")]
    public function changeCurrentAccount(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $user = UserService::getUserFromToken($request, $em);

        $success = AccountService::changeAccount($data["id"], $user, $em);

        if($success){
            return new JsonResponse("Success Update", 200);
        } else {
            return new JsonResponse("Server error", 500);
        }
    }

    /**
     * Pobranie balansu z aktualnego konta użytkownika.
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/account/current-account', name: 'get_current_account', methods: "GET")]
    public function getBalance(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $user->getCurrentAccount()]);

        return new JsonResponse(["balance" =>$account->getBalance(), "name" => $account->getName()], 200);
    }
}
