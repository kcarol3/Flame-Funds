<?php
declare(strict_types=1);
namespace App\Service\Strategy;

use App\Entity\User;
use App\Service\Interfaces\CategoryInterface;

class Category
{
    private array $strategies = [];

    /**
     * @param CategoryInterface $strategy
     * @return void
     */
    public function addStrategy(CategoryInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }


    /**
     * @param String $type
     * @param User $user
     * @param String $name
     * @param String $details
     * @return void
     * @throws \Exception
     */
    public function add(string $type,User $user,string $name,string $details): void
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getCategoryType() === $type) {
                $strategy->addCategory($user, $name, $details);
                return;
            }
        }
        throw new \Exception('Undefined category type');
    }

    /**
     * @param String $type
     * @param int $categoryId
     * @param string $newName
     * @param string $newDetails
     * @return void
     * @throws \Exception
     */
    public function edit(String $type, int $categoryId, string $newName, string $newDetails): void {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getCategoryType() === $type) {
                $strategy->editCategory($categoryId, $newName, $newDetails);
                return;
            }
        }
        throw new \Exception('Undefined category type');
    }

    /**
     * @param String $type
     * @param int $categoryId
     * @return void
     * @throws \Exception
     */
    public function remove(String $type, int $categoryId): void {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getCategoryType() === $type) {
                $strategy->removeCategory($categoryId);
                return;
            }
        }
        throw new \Exception('Undefined category type');
    }

    /**
     * @param String $type
     * @param int $categoryId
     * @return mixed
     * @throws \Exception
     */
    public function getOneById(String $type, int $categoryId) {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getCategoryType() === $type) {
                return $strategy->getCategory($categoryId);
            }
        }
        throw new \Exception('Undefined category type');
    }

    /**
     * @param String $type
     * @param User $user
     * @return mixed
     * @throws \Exception
     */
    public function getAll(String $type, User $user){
        foreach ($this->strategies as $strategy) {
            if ($strategy->getCategoryType() === $type) {
                return $strategy->getCategories($user);;
            }
        }
        throw new \Exception('Undefined category type');
    }

}