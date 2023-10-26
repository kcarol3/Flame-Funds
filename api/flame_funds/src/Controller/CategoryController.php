<?php

namespace App\Controller;

use App\Entity\ExpenseCategory;
use App\Entity\IncomeCategory;
use App\Service\Strategy\Category;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class CategoryController extends AbstractController
{
    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route('/category/{type}', name: 'get_categories', methods: 'GET')]
    public function getCategories(Request $request, EntityManagerInterface $em, $type): JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $categories = $this->category->getAll($type, $user);

        if($categories){
            return new JsonResponse($categories, 200);
        } else {
            return new JsonResponse(null, 400);
        }
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param $type
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/category/{type}/{id}', name: 'get_category', methods: 'GET')]
    public function getCategory(Request $request, EntityManagerInterface $em, $type, $id): JsonResponse
    {
        $category = $this->category->getOneById($type, $id);

        if($category){
            return new JsonResponse($category, 200);
        } else {
            return new JsonResponse(null, 400);
        }
    }

    #[Route('/category/{type}/{id}', name: 'edit_category', methods: 'put')]
    public function editCategory(Request $request, EntityManagerInterface $em, $type, $id){
        $content = $request->getContent();
        $data = json_decode($content, true);

        $this->category->edit($type, $id, $data['name'], $data['details']);

        return new Response("Success update");
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/category/{type}', name: 'add_expense_category', methods: 'POST')]
    public function addCategory(Request $request, EntityManagerInterface $em, $type): JsonResponse
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $user = UserService::getUserFromToken($request, $em);
        $this->category->add($type,$user, $data['name'], $data['details']);

        return new JsonResponse("Success saved category", 200);
    }
}
