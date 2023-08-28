<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class AccountService
{
    /**
     * @param int $accountId
     * @param User $user
     * @param EntityManagerInterface $em
     * @return true
     */
    public static function changeAccount(int $accountId, User $user, EntityManagerInterface $em){
        $user->setCurrentAccount($accountId);
        $em->flush();

        return true;
    }
}