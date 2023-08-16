<?php

namespace App\Controller;

use App\Entity\ExpenseCategory;
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
    #[Route('/category/add-expense', name: 'app_category')]
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

}
