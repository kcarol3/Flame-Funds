<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\ExpenseCategory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class DashboardService
{
    public static function getHistoryByDates(User $user, EntityManagerInterface $em): array{
        $accountId = $user->getCurrentAccount();

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $expenses = $account->getExpenses();
        $incomes = $account->getIncomes();
        $financialGoals  = $account->getFinancialGoal();
        $periodics = $account->getPeriodics();

        $dataToReturn = [];

        foreach ($expenses as $expense){
            if( !$expense->isIsDeleted()){
                $oneExpense = [];
                $oneExpense["id"] = $expense->getId();
                $oneExpense["name"] = $expense->getName();
                $oneExpense["amount"] = $expense->getAmount();
                $oneExpense["details"] = $expense->getDetails() ?? "";
                $oneExpense["type"] = "expense";
                $dataToReturn[$expense->getDate()->format("Y-m-d")][] = $oneExpense;
            }
        }

        foreach ($incomes as $income){
            if( !$income->isIsDeleted()){
                $oneIncome = [];
                $oneIncome["id"] = $income->getId();
                $oneIncome["name"] = $income->getName();
                $oneIncome["amount"] = $income->getAmount();
                $oneIncome["details"] = $income->getDetails() ?? "";
                $oneIncome["type"] = "income";
                $dataToReturn[$income->getDate()->format("Y-m-d")][] = $oneIncome;
            }
        }

        foreach ($financialGoals as $financialGoal){
            if( !$financialGoal->getIsDeleted()){
                $oneFinancialGoal = [];
                $oneFinancialGoal["name"] = $financialGoal->getName();
                $oneFinancialGoal["currentAmount"] = $financialGoal->getCurrentAmount();
                $oneFinancialGoal["details"] = $financialGoal->getDetails() ?? "";
                $oneFinancialGoal["type"] = "financialGoal";
                $dataToReturn[$financialGoal->getDateStart()->format("Y-m-d")][] = $oneFinancialGoal;
            }
        }

        foreach ($periodics as $periodic) {
            if (!$periodic->getIsDeleted()) {
                foreach ($periodic->getPeriodicDetails() as $periodicDetail) {
                    $onePeriodicDetail = [
                        "name" => $periodic->getName(),
                        "date" => $periodicDetail->getDate(),
                        "amount" => $periodicDetail->getAmount(),
                        "type" => "periodicDetail",
                    ];

                    $dataToReturn[$periodicDetail->getDate()->format("Y-m-d")][] = $onePeriodicDetail;
                }
            }
        }

        krsort($dataToReturn);

        return $dataToReturn;
    }

    public static function getMyFinancialGoalsByDates(User $user, EntityManagerInterface $em): array{
        $accountId = $user->getCurrentAccount();

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $financialGoals  = $account->getFinancialGoal();

        $dataToReturn = [];


        foreach ($financialGoals as $financialGoal){
            if( !$financialGoal->getIsDeleted()){
                $oneFinancialGoal = [];
                $oneFinancialGoal["id"] = $financialGoal->getId();
                $oneFinancialGoal["name"] = $financialGoal->getName();
                $oneFinancialGoal["currentAmount"] = $financialGoal->getCurrentAmount();
                $oneFinancialGoal["details"] = $financialGoal->getDetails() ?? "";
                $oneFinancialGoal["type"] = "financialGoal";
                $dataToReturn[$financialGoal->getDateStart()->format("Y-m-d")][] = $oneFinancialGoal;
            }
        }

        krsort($dataToReturn);

        return $dataToReturn;
    }

    public static function getMyPeriodicsByDates(User $user, EntityManagerInterface $em): array{
        $accountId = $user->getCurrentAccount();

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $periodics  = $account->getPeriodics();

        $dataToReturn = [];


        foreach ($periodics as $periodic){
            if( !$periodic->getIsDeleted()){
                $onePeriodic = [];
                $onePeriodic["id"] = $periodic->getId();
                $onePeriodic["name"] = $periodic->getName();
                $onePeriodic["amount"] = $periodic->getAmount();
                $onePeriodic["details"] = $periodic->getDetails() ?? "";
                $onePeriodic["days"] = $periodic->getDays();
                $onePeriodic["type"] = "periodic";
                $dataToReturn[$periodic->getDateStart()->format("Y-m-d")][] = $onePeriodic;
            }
        }

        krsort($dataToReturn);

        return $dataToReturn;
    }

    public static function getMonthlyAmountsByYear(User $user, EntityManagerInterface $em, $year): array
    {
        $accountId = $user->getCurrentAccount();

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $expenses = $account->getExpenses();
        $financialGoals = $account->getFinancialGoal();
        $periodics = $account->getPeriodics();

        $dataToReturn = [];

        for ($month = 1; $month <= 12; $month++) {
            $dataToReturn[$month] = 0;
        }

        foreach ($expenses as $expense) {
            if (!$expense->isIsDeleted() && $expense->getDate()->format('Y') == $year) {
                $month = $expense->getDate()->format('n');
                $dataToReturn[$month] += $expense->getAmount();
            }
        }

        foreach ($financialGoals as $financialGoal) {
            if (!$financialGoal->getIsDeleted() && $financialGoal->getDateStart()->format('Y') == $year) {
                $month = $financialGoal->getDateStart()->format('n');
                $dataToReturn[$month] += $financialGoal->getCurrentAmount();
            }
        }

        foreach ($periodics as $periodic) {
            if (!$periodic->getIsDeleted() && $periodic->getDateStart()->format('Y') == $year) {
                foreach ($periodic->getPeriodicDetails() as $periodicDetail) {
                    $month = $periodicDetail->getDate()->format('n');
                    $dataToReturn[$month] += $periodicDetail->getAmount();
                }
            }
        }

        return array_values($dataToReturn);
    }

    public static function getMonthlyIncomesByYear(User $user, EntityManagerInterface $em, $year): array
    {
        $accountId = $user->getCurrentAccount();

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $incomes = $account->getIncomes();

        $dataToReturn = [];

        for ($month = 1; $month <= 12; $month++) {
            $dataToReturn[$month] = 0;
        }

        foreach ($incomes as $income) {
            if (!$income->isIsDeleted() && $income->getDate()->format('Y') == $year) {
                $month = $income->getDate()->format('n');
                $dataToReturn[$month] += $income->getAmount();
            }
        }

        return array_values($dataToReturn);
    }

    public static function getQuarterlyAmountsByCategory(User $user, EntityManagerInterface $em, $year): array
    {
        $accountId = $user->getCurrentAccount();
        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->find($accountId);

        $expenses = $account->getExpenses();
        $categories = $em->getRepository(ExpenseCategory::class)->findAll(); // Załóżmy, że masz encję Category

        $dataToReturn = [];

        for ($quarter = 1; $quarter <= 4; $quarter++) {
            $dataToReturn[$quarter] = [];

            foreach ($categories as $category) {
                $dataToReturn[$quarter][$category->getName()] = 0;
            }
        }

        foreach ($expenses as $expense) {
            if (!$expense->isIsDeleted() && $expense->getDate()->format('Y') == $year) {
                $month = $expense->getDate()->format('n');
                $quarter = ceil($month / 3);
                $category = $expense->getCategory();
                $dataToReturn[$quarter][$category->getName()] += $expense->getAmount();
            }
        }


        return $dataToReturn;
    }

}