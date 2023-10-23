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
use Symfony\Component\HttpFoundation\Response;
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
    #[Route('/account', name: 'create_account', methods: 'POST')]
    public function createAccount(Request $request, EntityManagerInterface $em): JsonResponse{
        $content = $request->getContent();
        $jsonData = json_decode($content, true);

        $user = UserService::getUserFromToken($request, $em);

        if(!$user){
            return new JsonResponse("User does not exist", 500);
        }

        $accountService = new AccountService($em, $user);
        $accountService->addAccount($jsonData);

        return new JsonResponse("Success");
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/account', name: 'get_all_accounts', methods: "GET")]
    public function getAllAccounts(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);
        $accounts = $user->getAccounts();

        $accountService = new AccountService($em, $user);
        $dataToReturn = $accountService->getAllAccounts();

        return new JsonResponse($dataToReturn, 200);
    }

    /**
     * Zmiana aktualnego konta użytkownika na wybrane w formularzu.
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route("/account-change/{id}", name: 'change_account', methods: "PUT")]
    public function changeCurrentAccount(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $accountService = new AccountService($em, $user);
        $success = $accountService->changeAccount($id);

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
    #[Route('/account-current', name: 'get_current_account', methods: "GET")]
    public function getCurrentBalance(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $accountService = new AccountService($em, $user);
        $dataToReturn = $accountService->getBalance();

        return new JsonResponse($dataToReturn, 200);
    }

    #[Route('/account/{id}', name: 'delete_account', methods: "DELETE")]
    public function deleteAccount(Request $request, EntityManagerInterface $em, $id){
        $user = UserService::getUserFromToken($request, $em);

        $accountService = new AccountService($em, $user);
        $accountService->deleteAccount($id);

        return new Response("Success delete", 200);
    }

    #[Route('/account/{id}', name: 'change_account_name', methods: "PUT")]
    public function changeAccountName(Request $request, EntityManagerInterface $em, $id){
        $user = UserService::getUserFromToken($request, $em);
        $content = $request->getContent();
        $jsonData = json_decode($content, true);

        $accountService = new AccountService($em, $user);
        $accountService->changeAccountName($id, $jsonData["name"]);

        return new Response("Success change", 200);
    }
}
