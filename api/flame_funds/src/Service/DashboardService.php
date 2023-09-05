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

        $dataToReturn = [];

        foreach ($expenses as $expense){
            if( !$expense->isIsDeleted()){
                $oneExpense = [];
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
                $oneIncome["name"] = $income->getName();
                $oneIncome["amount"] = $income->getAmount();
                $oneIncome["details"] = $income->getDetails() ?? "";
                $oneIncome["type"] = "income";
                $dataToReturn[$income->getDate()->format("Y-m-d")][] = $oneIncome;
            }
        }

       krsort($dataToReturn);

        return $dataToReturn;
    }
}