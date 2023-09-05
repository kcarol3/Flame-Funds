<?php

namespace App\Controller;

use App\Entity\ExpenseCategory;
use App\Entity\IncomeCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CategoryController.php',
        ]);
    }
    #[Route('/category/get-expense', name: 'get_expense_categories', methods: 'GET')]
    public function getExpenseCategories(EntityManagerInterface $em){
        $categoryRepository = $em->getRepository(ExpenseCategory::class);

        $categories = $categoryRepository->findAll();

        $dataToReturn = [];
        foreach ($categories as $key => $category){
            if(!$category->isIsDeleted()){
                $oneRow = [];
                $oneRow["name"] = $category->getName();
                $oneRow["details"] = $category->getDetails() ?? "";
                $dataToReturn[] = $oneRow;
            }
        }

        if($dataToReturn){
            return new JsonResponse($dataToReturn, 200);
        } else {
            return new JsonResponse(null, 400);
        }
    }

    #[Route('/category/get-income', name: 'get_income_categories', methods: 'GET')]
    public function getIncomeCategories(EntityManagerInterface $em): JsonResponse
    {
        $categoryRepository = $em->getRepository(IncomeCategory::class);

        $categories = $categoryRepository->findAll();

        $dataToReturn = [];
        foreach ($categories as $key => $category){
            if(!$category->isIsDeleted()){
                $oneRow = [];
                $oneRow["name"] = $category->getName();
                $oneRow["details"] = $category->getDetails() ?? "";
                $dataToReturn[] = $oneRow;
            }
        }

        if($dataToReturn){
            return new JsonResponse($dataToReturn, 200);
        } else {
            return new JsonResponse(null, 400);
        }
    }


    #[Route('/category/add-expense', name: 'add_expense_category')]
    public function addExpenseCategory(Request $request, EntityManagerInterface $em){
        $content = $request->getContent();
        $data = json_decode($content, true);

        $newCategory = new ExpenseCategory();

        $newCategory->setName($data['name']);
        $newCategory->setDetails($data['details']);
        $newCategory->setIsDeleted(false);

        $em->persist($newCategory);
        $em->flush();

        return new JsonResponse("Success saved category", 200);
    }

    #[Route('/category/add-income', name: 'add_income_category')]
    public function addIncomeCategory(Request $request, EntityManagerInterface $em){
        $content = $request->getContent();
        $data = json_decode($content, true);

        $newCategory = new IncomeCategory();

        $newCategory->setName($data['name']);
        $newCategory->setDetails($data['details']);
        $newCategory->setIsDeleted(false);

        $em->persist($newCategory);
        $em->flush();

        return new JsonResponse("Success saved category", 200);
    }

}
