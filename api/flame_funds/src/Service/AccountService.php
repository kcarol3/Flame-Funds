<?php

namespace App\Service;

use App\Entity\Account;
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

        $this->em->persist($newAccount);
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
     * @return array
     */
    public function getBalance(){
        $accountRepository = $this->em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $this->user->getCurrentAccount()]);

        return ["balance" => $account->getBalance(), "name" => $account->getName()];
    }

    public function deleteAccount($accountID){
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
    public function changeAccountName(int $id, string $name){
        $accountRepository = $this->em->getRepository(Account::class);
        $account = $accountRepository->find($id);

        $account->setName($name);
        $this->em->flush();
    }
}