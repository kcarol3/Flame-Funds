<?php

namespace App\Service\Interfaces;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

abstract  class Transaction
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    abstract public function addTransaction(User $user, array $data);
    abstract public function  editTransaction(int $id, array $newData);
    abstract public function removeTransaction(int $id);
    abstract public function getTransactions(User $user);
    abstract public function getOneTransaction($id);
    abstract public function getTransactionType();

    /**
     * Dodaje stan konta do historii.
     * Kwota podana musi być ze znakiek zgodnym z typem transakcji np. expense z "-"
     * @param Account $account
     * @param \DateTimeInterface $date
     * @param $amount
     * @return void
     */
    public function addAccountBalanceToHistory(Account $account,\DateTimeInterface $date, $amount): void
    {
        $accountRepository = $this->em->getRepository(AccountHistory::class);
        $accountHistories = $accountRepository->findBy(['account' => $account, 'user' => $account->getUser()], ['date' => 'ASC']);

        $history = new AccountHistory();
        $history->setDate($date);
        $history->setUser($account->getUser());
        $history->setAccount($account);

        $isAdded = false;

        foreach ($accountHistories as $key => $accountHistory){
            if($accountHistory->getDate() > $date){
                if(!$isAdded) {
                    $history->setPreviousBalance($accountHistory->getPreviousBalance() + $amount);
                    $isAdded = true;
                }
                $accountHistory->setPreviousBalance($accountHistory->getPreviousBalance() + $amount);
            }
        }


        if(!$isAdded){
            $lastHistory = end($accountHistories);
            if($lastHistory->getDate() < $date){
                $history->setPreviousBalance($lastHistory->getPreviousBalance() + $amount);
            } else{
                $history->setPreviousBalance($accountHistories[0]->getPreviousBalance() + $amount * (-1));
            }
        }

        $this->em->persist($history);
        $this->em->flush();
    }
}