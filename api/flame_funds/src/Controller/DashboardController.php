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
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


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

        $lastElements = array_slice($dataToReturn, -10);
        return new JsonResponse($lastElements, 200);
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

        $today = new \DateTime('now');

        $periodics = $account->getPeriodics();
        $periodicDetailsRepository = $em->getRepository(PeriodicDetails::class);

        $dataToReturn = [];

        foreach ($periodics as $periodic) {
            if ($periodic->getIsDeleted()) {
                continue;
            }
            $interval = new \DateInterval("P" . $periodic->getDays() . "D");
            $missingDate = clone $periodic->getDateStart();
            $missingDate->setTime(0, 0, 0);
            while ($missingDate <= $today && $missingDate <= $periodic->getDateEnd()) {
                $existingDetails = $periodicDetailsRepository->findOneBy([
                    "date" => $missingDate,
                    "periodic" => $periodic->getId(),
                ]);
                if (!$existingDetails) {
                    $em->beginTransaction();
                    try {
                        $periodicDetails = new PeriodicDetails();
                        $periodicDetails->setDate($missingDate);
                        $periodicDetails->setAmount($periodic->getAmount());
                        $periodicDetails->setPeriodic($periodic);
                        $em->persist($periodicDetails);
                        $em->flush();
                        $em->commit();
                        $account->setBalance($account->getBalance() - $periodic->getAmount());
                        $dataToReturn[] = $periodicDetails;
                    } catch (\Exception $e) {
                        $em->rollback();
                        return new JsonResponse(['error' => 'Wystąpił błąd podczas zapisywania danych.'], 500);
                    }
                }
                $missingDate->add($interval);
            }
        }

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

    #[Route('/yearlyReport', name: 'yearlyReport', methods: 'GET')]
    public function getYearlyReport(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);
        $currentYear = date('Y');
        $data = DashboardService::getMonthlyAmountsByYear($user, $em, $currentYear);
        return new JsonResponse($data, 200);
    }

    #[Route('/yearlyIncomeReport', name: 'yearlyIncomeReport', methods: 'GET')]
    public function getYearlyIncomeReport(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);
        $currentYear = date('Y');
        $data = DashboardService::getMonthlyIncomesByYear($user, $em, $currentYear);
        return new JsonResponse($data, 200);
    }

    #[Route('/quarterReport', name: 'quarterReport', methods: 'GET')]
    public function getQuarterReport(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);
        $currentYear = date('Y');
        $data = DashboardService::getQuarterlyAmountsByCategory($user, $em, $currentYear);
        return new JsonResponse($data, 200);
    }


    #[Route('/generatePdf', name: 'generate_pdf', methods: 'GET')]
    public function generatePdf(Request $request, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator)
    {
        $user = UserService::getUserFromToken($request, $em);
        $currentYear = date('Y');
        $data = DashboardService::getMonthlyAmountsByYear($user, $em, $currentYear);
        $dataIncomes = DashboardService::getMonthlyIncomesByYear($user, $em, $currentYear);
        $dataFinancialGoals = DashboardService::getMyFinancialGoalsByDates($user, $em);

        $months = [
            'Styczeń', 'Luty', 'Marzec', 'Kwiecień', 'Maj', 'Czerwiec',
            'Lipiec', 'Sierpień', 'Wrzesień', 'Październik', 'Listopad', 'Grudzień'
        ];

        $header = "Roczne posumowanie finansowe $currentYear";

        //expense
        $resultExp = DashboardService::findMaxAndMinMonth($data, $months);
        $maxResultExp = $resultExp['max'];
        $minResultExp = $resultExp['min'];
        $spentYear = DashboardService::getSpentAmountYear($data);


        //income
        $resultInc = DashboardService::findMaxAndMinMonth($dataIncomes, $months);
        $maxResultInc = $resultInc['max'];
        $minResultInc = $resultInc['min'];
        $earnedYear = DashboardService::getEarnedAmountYear($dataIncomes);

        //financialgoal
        $realizedFinancialGoals = [];

        foreach ($dataFinancialGoals as $financialGoals) {
            foreach ($financialGoals as $financialGoal) {
                $currentAmount = floatval($financialGoal['currentAmount']);
                $goalAmount = floatval($financialGoal['goalAmount']);

                if ($currentAmount >= $goalAmount) {
                    $realizedFinancialGoals[] = [
                        'name' => $financialGoal['name'],
                        'goalAmount' => $goalAmount,
                        'currentAmount' => $currentAmount,
                    ];
                }
            }
        }

        $realizedFinancialGoalsTable = '<table>';
        $realizedFinancialGoalsTable .= '<tr><th>Cel</th><th>Zebrana kwota</th></tr>';

        foreach ($realizedFinancialGoals as $realizedGoal) {
            $realizedFinancialGoalsTable .= "<tr><td>{$realizedGoal['name']}</td><td>{$realizedGoal['currentAmount']} zl</td></tr>";
        }

        $realizedFinancialGoalsTable .= '</table>';


        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>PDF Generator</title>
        <style>
            body {
                font-family: 'liberation-sans', sans-serif;
                font-size: 26px; 
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>$header</h1>
        </header>
        <br>
        <table>
            <tr>
                <th>Zarobiono</th>
                <th>Wydano</th>
            </tr>
            <tr>
                <td>$earnedYear zl</td>
                <td>$spentYear zl</td>
            </tr>
        </table>
        <h4>Graniczne kwoty:</h4>
        <table>
            <tr>
                <th></th>
                <th>MAX</th>
                <th>MIN</th>
            </tr>
            <tr>
                <td>Przychód</td>
                <td>{$maxResultInc['month']}: {$maxResultInc['amount']} zł </td>
                <td>{$minResultInc['month']}: {$minResultInc['amount']} zł </td>
            </tr>
            <tr>
                <td>Wydatek</td>
                <td>{$maxResultExp['month']}: {$maxResultExp['amount']} zł</td>
                <td>{$minResultExp['month']}: {$minResultExp['amount']} zł</td>
            </tr>
        </table>
        <h4>Zrealizowane cele finansowe:</h4>
        $realizedFinancialGoalsTable
    </body>
    </html>
";

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'inline; filename="raport.pdf"');
        return $response;
    }


}
