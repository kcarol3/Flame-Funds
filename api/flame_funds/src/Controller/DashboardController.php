<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\Income;
use App\Entity\IncomeCategory;
use App\Entity\Periodic;
use App\Entity\PeriodicDetails;
use App\Service\DashboardService;
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

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'get_dashboard_data', methods: 'GET')]
    public function getDashboardData(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $accountRepository = $em->getRepository(Account::class);

        $account = $accountRepository->find($user->getCurrentAccount());

        $accountHistories = $account->getAccountHistories();

        $dataToReturn = [];
        foreach ($accountHistories as $accountHistory) {
            $oneHistory = [
                "y" => floatval($accountHistory->getPreviousBalance()),
                "x" => $accountHistory->getDate()->format("Y-m-d H:i:s"),
            ];
            $dataToReturn[] = $oneHistory;
        }

        usort($dataToReturn, function($a, $b) {
            return strtotime($a['x']) - strtotime($b['x']);
        });

        return new JsonResponse($dataToReturn, 200);
    }

    #[Route('/endfinancialgoalcheck', name: 'get_endfinancialgoal_check', methods: 'POST')]
    public function endFinancialGoalCheck(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();
        $content = $request->getContent();
        $data = json_decode($content, true);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        // Pobierz bieżącą datę
        $today = new \DateTime();
        $today->setTime(0, 0, 0);

        $periodics = $account->getPeriodics();

        dd($periodics);

        $periodicDetailsRepository = $em->getRepository(PeriodicDetails::class);

        $dataToReturn = [];

        foreach ($periodics as $periodic) {
            $dateDiff = $today->diff($periodic->getDateStart());
            $daysBetween = $today->diff($periodic->getDateStart())->days;

            if ($periodic->getIsDeleted() == 0) {
                for ($i = $daysBetween - $periodic->getDays(); $i >= 0; $i -= $periodic->getDays()) {
                    $missingDate = clone $periodic->getDateStart();
                    $missingDate->modify("+$i days");

                    // Dodaj warunek, żeby pomijać datę startową
                    if ($missingDate != $periodic->getDateStart()) {
                        $existingDetails = $periodicDetailsRepository->findOneBy([
                            "date" => $missingDate,
                            "periodic" => $periodic->getId(),
                        ]);

                        if (!$existingDetails) {
                            $periodicDetails = new PeriodicDetails();
                            $periodicDetails->setDate($missingDate);
                            $periodicDetails->setAmount($periodic->getAmount());
                            $periodicDetails->setPeriodic($periodic);

                            $em->persist($periodicDetails);
                            $dataToReturn[] = $periodicDetails;
                        }
                    }
                }
            }
        }
        $em->flush();

        return new JsonResponse($dataToReturn, 200);
    }


    #[Route('/history', name: 'get_history_data', methods: 'GET')]
    public function getHistory(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $data = DashboardService::getHistoryByDates($user, $em);
        return new JsonResponse($data, 200);
    }

    #[Route('/myfinancialgoals', name: 'get_myfinancialgoals_data', methods: 'GET')]
    public function getMyFinancialGoals(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $data = DashboardService::getMyFinancialGoalsByDates($user, $em);
        return new JsonResponse($data, 200);
    }

    #[Route('/myPeriodics', name: 'get_myperiodics_data', methods: 'GET')]
    public function getMyPeriodics(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $data = DashboardService::getMyPeriodicsByDates($user, $em);
        return new JsonResponse($data, 200);
    }
}
