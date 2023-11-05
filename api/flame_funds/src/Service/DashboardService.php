<?php

namespace App\Service;

use App\Entity\Account;
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

        foreach ($periodics as $periodic){
            if( !$periodic->getIsDeleted()){
                $onePeriodic = [];
                $onePeriodic["name"] = $periodic->getName();
                $onePeriodic["amount"] = $periodic->getAmount();
                $onePeriodic["details"] = $periodic->getDetails() ?? "";
                $onePeriodic["type"] = "periodic";
                $dataToReturn[$periodic->getDateStart()->format("Y-m-d")][] = $onePeriodic;
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
}