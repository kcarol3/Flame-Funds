<?php
declare(strict_types=1);
namespace App\Service;

use App\Entity\ExpenseCategory;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ExpenseCategoryService implements Interfaces\CategoryInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    /**
     * @param User $user
     * @param string $name
     * @param string $details
     * @return void
     */
    public function addCategory(User $user, string $name, string $details): void
    {
        $newCategory = new ExpenseCategory();

        $newCategory->setUser($user);
        $newCategory->setName($name);
        $newCategory->setDetails($details);
        $newCategory->setIsDeleted(false);

        $this->em->persist($newCategory);
        $this->em->flush();
    }

    /**
     * @param int $categoryId
     * @return void
     */
    public function removeCategory(int $categoryId)
    {
        $categoryRepository = $this->em->getRepository(ExpenseCategory::class);
        $category = $categoryRepository->find($categoryId);

        $category->setIsDeleted(true);

        $this->em->flush();
    }

    /**
     * @param int $categoryId
     * @param string $newName
     * @param string $newDetails
     * @return void
     */
    public function editCategory(int $categoryId, string $newName, string $newDetails)
    {
        $categoryRepository = $this->em->getRepository(ExpenseCategory::class);
        $category = $categoryRepository->find($categoryId);

        $category->setName($newName);
        $category->setDetails($newDetails);

        $this->em->flush();
    }

    /**
     * @param int $categoryId
     * @return array|false
     */
    public function getCategory(int $categoryId): bool|array
    {
        $categoryRepository = $this->em->getRepository(ExpenseCategory::class);
        $category = $categoryRepository->find($categoryId);

        if($category->isIsDeleted()){
            return false;
        } else {
            return ["name" => $category->getName(), "details" => $category->getDetails()];
        }
    }

    /**
     * @param User $user
     * @return array
     */
    public function getCategories(User $user): array
    {
        $categoryRepository = $this->em->getRepository(ExpenseCategory::class);
        $categories =  $categoryRepository->findBy(['user' => $user, "isDeleted" => false]);

        $dataToReturn = [];
        foreach ($categories as $key => $category){
            if(!$category->isIsDeleted()){
                $oneRow = [];
                $oneRow["name"] = $category->getName();
                $oneRow["details"] = $category->getDetails() ?? "";
                $dataToReturn[] = $oneRow;
            }
        }

        return $dataToReturn;
    }

    /**
     * @return string
     */
    public function getCategoryType(): string
    {
        return 'expense';
    }
}