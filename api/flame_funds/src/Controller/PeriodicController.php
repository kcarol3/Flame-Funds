<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\Periodic;
use App\Entity\ExpenseCategory;
use App\Entity\PeriodicDetails;
use App\Service\AccountService;
use App\Service\DashboardService;
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
class PeriodicController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/periodic/add-periodic', name: 'add_periodic', methods: 'POST')]
    public function addPeriodic(Request $request, EntityManagerInterface $em){
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $content = $request->getContent();
        $data = json_decode($content, true);

        $dateStart = new \DateTime($data['dateStart']);
        $dateEnd = new \DateTime($data['dateEnd']);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        $categoryRepository = $em->getRepository(ExpenseCategory::class);
        $category = $categoryRepository->findOneBy(["name" => $data["category"]["name"]]);

        $periodic = new Periodic();
        $periodic->setName($data["name"]);
        $periodic->setAmount($data["amount"]);
        $periodic->setDateStart($dateStart);
        $periodic->setDateEnd($dateEnd);
        $periodic->setDetails($data["details"]);
        $periodic->setDays($data["days"]);
        $periodic->setCategory($category);
        $periodic->setAccount($account);
        $periodic->setIsDeleted(false);

        $em->persist($periodic);

        $account->setBalance($account->getBalance() - $data["amount"]);

        $history = new AccountHistory();
        $history->setDate($dateStart);
        $history->setPreviousBalance($account->getBalance());
        $history->setUser($account->getUser());
        $history->setAccount($account);

        $em->persist($history);

        $em->flush();

        return new JsonResponse("Success saved periodic", 200);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/periodic/all-periodics', name: 'get_all_periodics', methods: "GET")]
    public function getAllPeriodics(Request $request, EntityManagerInterface $em): JsonResponse
    {

        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $periodics = $account->getPeriodics();

        $dataToReturn = [];

        foreach ($periodics as $periodic) {
            if (!$periodic->getIsDeleted()) {
                $dataToReturn[] = [
                    "id" => $periodic->getId(),
                    "name" => $periodic->getName(),
                    "amount" => $periodic->getAmount(),
                    "dateEnd" => $periodic->getDateEnd(),
                    "dateStart" => $periodic->getDateStart(),
                    "days" => $periodic->getDays(),
                    "details" => $periodic->getDetails()
                ];

                // Sprawdź, czy data płatności przypada dzisiaj lub wcześniej
                $currentDate = new \DateTime();

                // Jeśli pole "days" jest ustawione, to przetwarzaj tylko w dniach zdefiniowanych w "days"
                if ($periodic->getDays()) {
                    $currentDay = $currentDate->format('j');
                    $paymentDays = explode(",", $periodic->getDays());
                    if (in_array((string) $currentDay, $paymentDays)) {
                        $this->processPayment($em, $account, $currentDate);
                    }
                } else {
                    $this->processPayment($em, $account, $currentDate);
                }
            }
        }

        $em->flush();

        return new JsonResponse($dataToReturn, 200);
    }

// Metoda do przetwarzania płatności i dodawania rekordu do bazy danych
    private function processPayment(EntityManagerInterface $em, Account $account, \DateTime $currentDate)
    {
        // Tutaj dodaj logikę przetwarzania płatności i dodawania rekordu do bazy danych
        $history = new AccountHistory();
        $history->setDate($currentDate);
        $history->setPreviousBalance($account->getBalance());
        $history->setUser($account->getUser());
        $history->setAccount($account);

        $em->persist($history);

        // Tutaj możesz dodać więcej logiki lub operacji na płatnościach

        // Oznacz płatność cykliczną jako przetworzoną, aby uniknąć wielokrotnego przetwarzania
        //$periodic->setIsProcessed(true);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/periodic/delete-periodic/{id}', name: 'delete_periodic', methods: 'DELETE')]
    public function deletePeriodic(Request $request, EntityManagerInterface $em, int $id)
    {
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $PeriodicRepository = $em->getRepository(Periodic::class);
        $periodic = $PeriodicRepository->findOneBy(["id" => $id]);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        if (!$periodic) {
            return new JsonResponse("Płatność cykliczna nie została znaleziona", 404);
        }

        $periodic->setIsDeleted(true);

        $periodicDetailsRepository = $em->getRepository(PeriodicDetails::class);
        $periodicDetailsList = $periodicDetailsRepository->findBy(["periodic" => $periodic]);

        foreach ($periodicDetailsList as $periodicDetails) {
            $em->remove($periodicDetails);
            $account->setBalance($account->getBalance() + $periodicDetails->getAmount());
        }

        $account->setBalance($account->getBalance() + $periodic->getAmount());

        $em->flush();

        return new JsonResponse("Płatność cykliczna została usunięta razem z jej detalami", 200);
    }


    #[Route('/myPeriodics', name: 'get_myperiodics_data', methods: 'GET')]
    public function getMyPeriodics(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $data = DashboardService::getMyPeriodicsByDates($user, $em);
        return new JsonResponse($data, 200);
    }
}
