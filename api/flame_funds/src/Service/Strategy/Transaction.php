<?php

namespace App\Service\Strategy;

use App\Entity\User;

class Transaction
{
    private array $strategies = [];

    public function addStrategy(\App\Service\Interfaces\Transaction $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    public function add(string $type,User $user, array $data): void
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getTransactionType() === $type) {
                $strategy->addTransaction($user, $data);
                return;
            }
        }
        throw new \Exception('Undefined transaction type');
    }

    public function edit(String $type, int $transactionId, array $newData): void {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getTransactionType() === $type) {
                $strategy->editTransaction($transactionId, $newData);
                return;
            }
        }
        throw new \Exception('Undefined transaction type');
    }

    public function remove(String $type, int $transactionId): void {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getTransactionType() === $type) {
                $strategy->removeTransaction($transactionId);
                return;
            }
        }
        throw new \Exception('Undefined transaction type');
    }

    public function getOneById(String $type, int $transactionId) {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getTransactionType() === $type) {
                return $strategy->getOneTransaction($transactionId);
            }
        }
        throw new \Exception('Undefined transaction type');
    }
}