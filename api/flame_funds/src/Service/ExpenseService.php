<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $accountID
     * @param string $name
     * @param float $amount
     * @param \DateTime $date
     * @param string $describe
     * @return bool
     */
    public function addExpense(int $accountID, string $name, float $amount, \DateTime $date, string $describe, string $categoryName): bool
    {
        $accountRepository = $this->entityManager->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountID]);

        $expense = new Expense();
        $expense->setName($name);
        $expense->setAccount($account);
        $expense->setDate($date);
        $expense->setAmount($amount);
        $expense->setIsDeleted(false);

        $categoryRepository = $this->entityManager->getRepository(ExpenseCategory::class);
        $category = $categoryRepository->findOneBy(["name" => $categoryName]);

        $expense->setCategory($category);
        if($describe != ""){
            $expense->setDetails($describe);
        }

        $account->setBalance($account->getBalance() - $amount);

        $this->entityManager->persist($expense);
        $this->entityManager->flush();

        return true;

    }
}