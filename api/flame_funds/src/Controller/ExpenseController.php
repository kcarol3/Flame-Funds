<?php

namespace App\Controller;

use App\Service\TransactionsService;
use App\Service\TransactionsServices\ExpenseService;
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
class ExpenseController extends AbstractController
{
    private $em;
    private ExpenseService $expenseService;

    public function __construct(EntityManagerInterface $em, ExpenseService $transactionService)
    {
        $this->em = $em;
        $this->expenseService = $transactionService;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/expense/add-expense', name: 'add_expense', methods: 'POST')]
    public function addExpense(Request $request){
        $user = UserService::getUserFromToken($request, $this->em);
        $accountId = $user->getCurrentAccount();

        $content = $request->getContent();
        $data = json_decode($content, true);

        $data['date'] = new \DateTime($data['date']);

        $this->expenseService->addTransaction($user, $data);

        return new JsonResponse("Success saved expense", 200);
    }


}
