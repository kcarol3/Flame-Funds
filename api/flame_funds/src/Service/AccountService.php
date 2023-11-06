<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccountService
{
    private $em;
    private $user;

    public function __construct(EntityManagerInterface $em, User $user)
    {
        $this->em = $em;
        $this->user = $user;
    }

    /**
     * @param int $accountId
     * @return true
     */
    public function changeAccount(int $accountId){
        $this->user->setCurrentAccount($accountId);
        $this->em->flush();

        return true;
    }

    /**
     * @param array $dataFromRequest
     * @return void
     */
    public function addAccount(Array $dataFromRequest){
        $newAccount = new Account();
        $newAccount->setUser($this->user);
        $newAccount->setBalance($dataFromRequest['balance']);
        $currentDateTime = new DateTime();
        $newAccount->setCreatedDate($currentDateTime);
        $newAccount->setName($dataFromRequest['name']);
        $newAccount->setIsDeleted(false);

        $accountHistory = new AccountHistory();
        $accountHistory->setAccount($newAccount);
        $accountHistory->setUser($this->user);
        $accountHistory->setDate($currentDateTime);
        $accountHistory->setPreviousBalance($dataFromRequest['balance']);

        $this->em->persist($newAccount);
        $this->em->persist($accountHistory);
        $this->em->flush();
    }

    /**
     * @return array
     */
    public function getAllAccounts(){
        $accounts = $this->user->getAccounts();
        $dataToReturn = [];

        foreach ($accounts as $account){
            if(!$account->isIsDeleted()){
                $dataToReturn[] = [
                    "id" => $account->getId(),
                    "name" => $account->getName(),
                    "balance" => $account->getBalance()
                ];
            }
        }

        return $dataToReturn;
    }

    /**
     * @return array|null
     */
    public function getBalance(): ?array
    {
        $accountRepository = $this->em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $this->user->getCurrentAccount()]);
        if($account){
            return ["balance" => $account->getBalance(), "name" => $account->getName()];

        } else {
            return null;
        }
    }

    /**
     * @param $accountID
     * @return void
     */
    public function deleteAccount($accountID): void
    {
        $accountRepository = $this->em->getRepository(Account::class);
        $account = $accountRepository->find($accountID);

        if($this->user->getCurrentAccount() == $accountID){
            $this->user->setCurrentAccount(null);
        }

        $account->setIsDeleted(true);

        $expensesOnAccount = $account->getExpenses();
        foreach ($expensesOnAccount as $expense) {
            $expense->setIsDeleted(true);
        }

        $incomeOnAccount = $account->getIncomes();
        foreach ($incomeOnAccount as $income) {
            $income->setIsDeleted(true);
        }

        $this->em->flush();
    }

    /**
     * @param int $id
     * @param string $name
     * @return void
     */
    public function changeAccountName(int $id, string $name): void
    {
        $accountRepository = $this->em->getRepository(Account::class);
        $account = $accountRepository->find($id);

        $account->setName($name);
        $this->em->flush();
    }
}