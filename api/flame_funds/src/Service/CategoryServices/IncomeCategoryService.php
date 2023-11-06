<?php

namespace App\Service\CategoryServices;

use App\Entity\IncomeCategory;
use App\Entity\User;
use App\Service\Interfaces;
use Doctrine\ORM\EntityManagerInterface;

class IncomeCategoryService implements Interfaces\CategoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    public function addCategory(User $user, string $name, string $details)
    {
        $newCategory = new IncomeCategory();

        $newCategory->setUser($user);
        $newCategory->setName($name);
        $newCategory->setDetails($details);
        $newCategory->setIsDeleted(false);

        $this->em->persist($newCategory);
        $this->em->flush();
    }

    public function removeCategory(int $categoryId)
    {
        $categoryRepository = $this->em->getRepository(IncomeCategory::class);
        $category = $categoryRepository->find($categoryId);

        $category->setIsDeleted(true);

        $this->em->flush();
    }

    public function editCategory(int $categoryId, string $newName, string $newDetails)
    {
        $categoryRepository = $this->em->getRepository(IncomeCategory::class);
        $category = $categoryRepository->find($categoryId);

        $category->setName($newName);
        $category->setDetails($newDetails);

        $this->em->flush();
    }

    public function getCategory(int $categoryId)
    {
        $categoryRepository = $this->em->getRepository(IncomeCategory::class);
        $category = $categoryRepository->find($categoryId);

        if($category->isIsDeleted()){
            return false;
        } else {
            return ["id" => $category->getId(), "name" => $category->getName(), "details" => $category->getDetails()];
        }
    }

    public function getCategories(User $user)
    {
        $categoryRepository = $this->em->getRepository(IncomeCategory::class);
        $categories =  $categoryRepository->findBy(['user' => $user, "isDeleted" => false]);

        $dataToReturn = [];
        foreach ($categories as $key => $category){
            if(!$category->isIsDeleted()){
                $oneRow = [];
                $oneRow["id"] = $category->getId();
                $oneRow["name"] = $category->getName();
                $oneRow["details"] = $category->getDetails() ?? "";
                $dataToReturn[] = $oneRow;
            }
        }

        return $dataToReturn;
    }

    public function getCategoryType()
    {
        return "income";
    }
}