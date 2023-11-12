<?php

namespace App\Service\TransactionsServices;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\Income;
use App\Entity\IncomeCategory;
use App\Entity\User;
use App\Service\Interfaces\Transaction;

class IncomeService extends Transaction
{

    public function addTransaction(User $user, array $data)
    {
        $accountRepository = $this->em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $user->getCurrentAccount()]);

        $income = new Income();
        $income->setName($data['name']);
        $income->setAccount($account);
        $income->setDate($data['date']);
        $income->setAmount($data['amount']);
        $income->setIsDeleted(false);

        $categoryRepository = $this->em->getRepository(IncomeCategory::class);
        $category = $categoryRepository->findOneBy(["name" => $data['category']['name']]);

        $income->setCategory($category);
        if ($data['describe'] != "") {
            $income->setDetails($data['describe']);
        }

        $this->em->persist($income);

        $account->setBalance($account->getBalance() + $data['amount']);

        $this->addAccountBalanceToHistory($account, $data['date'],  $data['amount']);

        $this->em->flush();
    }

    public function editTransaction(int $id, array $newData)
    {
        $incomeRepository = $this->em->getRepository(Income::class);
        $income = $incomeRepository->find($id);

        if ($income && !$income->isIsDeleted()) {
            $isAmountModifed = false;
            $previousDate = $income->getDate();
            $previousAmount = $income->getAmount();

            if (isset($newData['name'])) {
                $income->setName($newData['name']);
            }


            if (isset($newData['amount'])) {
                $income->setAmount($newData['amount']);
                $isAmountModifed = true;
            }

            if (isset($newData['details'])) {
                if ($newData['details'] !== "") {
                    $income->setDetails($newData['details']);
                }
            }

            if (isset($newData['category']['name'])) {
                $categoryRepository = $this->em->getRepository(IncomeCategory::class);
                $newCategory = $categoryRepository->findOneBy(["name" => $newData['category']['name']]);
                $income->setCategory($newCategory);
            }

            if ($isAmountModifed) {
                $accHisRepo = $this->em->getRepository(AccountHistory::class);

                $accountHistories = $accHisRepo->findBy(['account' => $income->getAccount(), 'user' => $income->getAccount()->getUser()]);
                if ($accountHistories) {
                    foreach ($accountHistories as $key => $oneHistory) {
                        if ($oneHistory->getDate() >= $previousDate) {
                            $newBalance = $oneHistory->getPreviousBalance() - $previousAmount + $income->getAmount();
                            $oneHistory->setPreviousBalance($newBalance);
                            $income->getAccount()->setBalance($income->getAccount()->getBalance() - $previousAmount + $income->getAmount());
                        }
                    }
                }
            }
        }
        $this->em->flush();
    }

    public function removeTransaction(int $id)
    {
        $incomeRepository = $this->em->getRepository(Income::class);
        $income = $incomeRepository->find($id);
        $income->setIsDeleted(true);

        $accHisRepo = $this->em->getRepository(AccountHistory::class);
        $accountHistories = $accHisRepo->findBy(['account' => $income->getAccount(), 'user' => $income->getAccount()->getUser()], ['date' => 'ASC']);

        foreach ($accountHistories as $key => $accountHistory) {
            if($accountHistory->getDate() == $income->getDate()){
                $this->em->remove($accountHistory);
            }
            if ($accountHistory->getDate() > $income->getDate()){
                $newBalance = $accountHistory->getPreviousBalance() - $income->getAmount();
                $accountHistory->setPreviousBalance($newBalance);
            }
        }

        $income->getAccount()->setBalance($income->getAccount()->getBalance() - $income->getAmount());
        $this->em->flush();
    }

    public function getTransactions(User $user)
    {
        // TODO: Implement getTransactions() method.
    }

    public function getOneTransaction($id)
    {
        $repository = $this->em->getRepository(Income::class);
        $income = $repository->find($id);

        return [
            "id" => $income->getId(),
            "name" => $income->getName(),
            "amount" => $income->getAmount(),
            "date" => $income->getDate()->format("Y d/m/y H:i"),
            "category" => $income->getCategory()->getName(),
            "details" => $income->getDetails()
        ];
    }

    public function getTransactionType()
    {
        return "income";
    }
}