<?php

namespace App\Controller;

use App\Service\TransactionsService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class ExpenseController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/expense/add-expense', name: 'add_expense', methods: 'POST')]
    public function addExpense(Request $request, EntityManagerInterface $em){
        $expenseService = new TransactionsService($em);

        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $content = $request->getContent();
        $data = json_decode($content, true);

        $date = new \DateTime($data['date']);


        if ($expenseService->addExpense($accountId, $data['name'], $data['amount'], $date, $data['describe'], $data['category']['name'])){
            return new JsonResponse("Success saved expense", 200);
        } else {
            return new JsonResponse("Server Error", 500);
        }
    }
}
