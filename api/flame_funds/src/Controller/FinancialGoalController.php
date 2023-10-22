<?php

namespace App\Controller;

use App\Entity\FinancialGoal;
use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\User;
use App\Service\AccountService;
use App\Service\TransactionsService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api", name="api_")
 */
class FinancialGoalController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/financialGoal/add-financialGoal', name: 'add_financialGoal', methods: 'POST')]
    public function addFinancialGoal(Request $request, EntityManagerInterface $em){
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $content = $request->getContent();
        $data = json_decode($content, true);

        $dateStart = new \DateTime($data['dateStart']);
        $dateEnd = new \DateTime($data['dateEnd']);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        $financialGoal = new FinancialGoal();
        $financialGoal->setDateStart($dateStart);
        $financialGoal->setDateEnd($dateEnd);
        $financialGoal->setName($data["name"]);
        $financialGoal->setGoalAmount($data["goalAmount"]);
        $financialGoal->setCurrentAmount($data["currentAmount"]);
        $financialGoal->setDetails($data["details"]);
        $financialGoal->setAccount($account);
        $financialGoal->setIsDeleted(false);

        $em->persist($financialGoal);

        $account->setBalance($account->getBalance() - $data["currentAmount"]);

        $history = new AccountHistory();
        $history->setDate($dateStart);
        $history->setPreviousBalance($account->getBalance());
        $history->setUser($account->getUser());
        $history->setAccount($account);

        $em->persist($history);

        $em->flush();

        return new JsonResponse("Success saved financial goal", 200);

    }

//    /**
//     * @param Request $request
//     * @param EntityManagerInterface $em
//     * @return JsonResponse
//     */
//    #[Route('/financialGoal/edit-financialGoal', name: 'edit_financialGoal', methods: "PUT")]
//    public function editFinancialGoal(Request $request, EntityManagerInterface $em, int $id): JsonResponse
//    {
//        $user = UserService::getUserFromToken($request, $em);
//
//        // Pobierz dane z żądania
//        $content = $request->getContent();
//        $data = json_decode($content, true);
//
//        // Znajdź cel finansowy na podstawie ID
//        $financialGoalRepository = $em->getRepository(FinancialGoal::class);
//        $financialGoal = $financialGoalRepository->find($id);
//
//        if (!$financialGoal) {
//            return new JsonResponse("Cel finansowy nie istnieje", 404);
//        }
//
//        // Sprawdź, czy cel finansowy należy do użytkownika
//        if ($financialGoal->getAccount()->getUser() !== $user) {
//            return new JsonResponse("Nie masz uprawnień do edycji tego celu finansowego", 403);
//        }
//
//        // Zaktualizuj currentAmount
//        $currentAmount = $data['currentAmount'];
//        $financialGoal->setCurrentAmount($currentAmount);
//
//        // Zapisz zmiany w bazie danych
//        $em->flush();
//
//        return new JsonResponse("Cel finansowy został zaktualizowany", 200);
//    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/financialGoal/all-financialGoals', name: 'get_all_financialGoals', methods: "GET")]
    public function getAllFinancialGoals(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $financialGoals  = $account->getFinancialGoal();

        $dataToReturn = [];

        foreach ($financialGoals as $financialGoal){
            if( !$financialGoal->getIsDeleted()){
                $dataToReturn[] = [
                    "id" => $financialGoal->getId(),
                    "name" => $financialGoal->getName(),
                    "currentAmount" => $financialGoal->getCurrentAmount(),
                    "goalAmount" => $financialGoal->getGoalAmount(),
                    "dateEnd" => $financialGoal->getDateEnd()
                ];
            }
        }

        return new JsonResponse($dataToReturn, 200);
    }


    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/financialGoal/delete-financialGoal/{id}', name: 'delete_financialGoal', methods: 'DELETE')]
    public function deleteFinancialGoal(Request $request, EntityManagerInterface $em, int $id)
    {
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        // Znajdź cel finansowy na podstawie przekazanego identyfikatora
        $financialGoalRepository = $em->getRepository(FinancialGoal::class);
        $financialGoal = $financialGoalRepository->findOneBy(["id" => $id]);

        if (!$financialGoal) {
            return new JsonResponse("Cel finansowy nie został znaleziony", 404);
        }

        // Oznacz cel finansowy jako usunięty (ustaw flagę isDeleted na true)
        $financialGoal->setIsDeleted(true);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        $account->setBalance($account->getBalance() + $financialGoal->getCurrentAmount());

        // Zapisz zmiany w bazie danych
        $em->flush();

        return new JsonResponse("Cel finansowy został usunięty", 200);
    }

    #[Route('/financialGoal/addCurrentAmount/{id}', name: 'add_current_amount', methods: "PUT")]
    public function addCurrentAmount(Request $request, EntityManagerInterface $em, $id)
    {
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();
        $content = $request->getContent();
        $jsonData = json_decode($content, true);

        $financialGoalRepository = $em->getRepository(FinancialGoal::class);
        $financialGoal = $financialGoalRepository->find($id);

        if (!$financialGoal) {
            return new JsonResponse("Cel finansowy nie został znaleziony", 404);
        }

        $beforeAddAmount = $financialGoal->getCurrentAmount();
        $financialGoal->setCurrentAmount($beforeAddAmount + $jsonData["currentAmount"]);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        $account->setBalance($account->getBalance() - $jsonData["currentAmount"]);

        $em->flush();

        return new Response("Success add", 200);
    }

}
