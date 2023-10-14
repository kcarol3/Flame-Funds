<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\AccountHistory;
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
}
