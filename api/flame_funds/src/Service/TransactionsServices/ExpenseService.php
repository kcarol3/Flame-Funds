<?php

namespace App\Service\TransactionsServices;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\Expense;
use App\Entity\ExpenseCategory;
use App\Entity\User;
use App\Repository\ExpenseRepository;
use App\Service\Interfaces\Transaction;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseService extends Transaction
{
    public function addTransaction(User $user, array $data)
    {
        $accountRepository = $this->em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $user->getCurrentAccount()]);

        $expense = new Expense();
        $expense->setName($data['name']);
        $expense->setAccount($account);
        $expense->setDate($data['date']);
        $expense->setAmount($data['amount']);
        $expense->setIsDeleted(false);

        $categoryRepository = $this->em->getRepository(ExpenseCategory::class);
        $category = $categoryRepository->findOneBy(["name" => $data['category']['name']]);

        $expense->setCategory($category);
        if ($data['describe'] != "") {
            $expense->setDetails($data['describe']);
        }

        $this->em->persist($expense);

        $account->setBalance($account->getBalance() - $data['amount']);

        $this->addAccountBalanceToHistory($account, $data['date'], -1 * $data['amount']);

        $this->em->flush();
    }

    /**
     * @throws \Exception
     */
    public function editTransaction(int $id, array $newData)
    {
        $expenseRepository = $this->em->getRepository(Expense::class);
        $expense = $expenseRepository->find($id);

        if ($expense && !$expense->isIsDeleted()) {
            $isAmountModifed = false;
            $previousDate = $expense->getDate();
            $previousAmount = $expense->getAmount();

            if (isset($newData['name'])) {
                $expense->setName($newData['name']);
            }


            if (isset($newData['amount'])) {
                $expense->setAmount($newData['amount']);
                $isAmountModifed = true;
            }

            if (isset($newData['details'])) {
                if ($newData['details'] !== "") {
                    $expense->setDetails($newData['details']);
                }
            }

            if (isset($newData['category']['name'])) {
                $categoryRepository = $this->em->getRepository(ExpenseCategory::class);
                $newCategory = $categoryRepository->findOneBy(["name" => $newData['category']['name']]);
                $expense->setCategory($newCategory);
            }

            if ($isAmountModifed) {
                $accHisRepo = $this->em->getRepository(AccountHistory::class);

                $accountHistories = $accHisRepo->findBy(['account' => $expense->getAccount(), 'user' => $expense->getAccount()->getUser()]);
                if ($accountHistories) {
                    foreach ($accountHistories as $key => $oneHistory) {
                        if ($oneHistory->getDate() >= $previousDate) {
                            $newBalance = $oneHistory->getPreviousBalance() + $previousAmount - $expense->getAmount();
                            $oneHistory->setPreviousBalance($newBalance);
                            $expense->getAccount()->setBalance($expense->getAccount()->getBalance() + $previousAmount - $expense->getAmount());
                        }
                    }
                }
            }
        }
        $this->em->flush();
    }

    public
    function removeTransaction(int $id)
    {
        $expenseRepository = $this->em->getRepository(Expense::class);
        $expense = $expenseRepository->find($id);
        $expense->setIsDeleted(true);

        $accHisRepo = $this->em->getRepository(AccountHistory::class);
        $accountHistories = $accHisRepo->findBy(['account' => $expense->getAccount(), 'user' => $expense->getAccount()->getUser()], ['date' => 'ASC']);

        foreach ($accountHistories as $key => $accountHistory) {
            if($accountHistory->getDate() == $expense->getDate()){
                $this->em->remove($accountHistory);
            }
            if ($accountHistory->getDate() > $expense->getDate()){
                $newBalance = $accountHistory->getPreviousBalance() + $expense->getAmount();
                $accountHistory->setPreviousBalance($newBalance);
            }
        }

        $expense->getAccount()->setBalance($expense->getAccount()->getBalance() + $expense->getAmount());
        $this->em->flush();
    }

    public
    function getTransactions(User $user)
    {
        $repository = $this->em->getRepository(Expense::class);
        $expenses = $repository->findBy(["user"=>$user, "account"=>$user->getCurrentAccount()]);

    }

    public
    function getOneTransaction($id)
    {
        $repository = $this->em->getRepository(Expense::class);
        $expense = $repository->find($id);

        return [
            "id" => $expense->getId(),
            "name" => $expense->getName(),
            "amount" => $expense->getAmount(),
            "date" => $expense->getDate()->format("Y d/m/y H:i"),
            "category" => $expense->getCategory()->getName(),
            "details" => $expense->getDetails()
        ];

    }

    public
    function getTransactionType(): string
    {
        return "expense";
    }
}