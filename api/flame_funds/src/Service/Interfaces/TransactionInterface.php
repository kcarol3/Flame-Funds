<?php

namespace App\Service\Interfaces;

use App\Entity\User;

interface TransactionInterface
{
    public function addTransaction(User $user, array $data);
    public function  editTransaction(int $id, array $newData);
    public function removeTransaction(int $id);
    public function getTransactions(User $user);
    public function getOneTransaction($id);
}