<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\Income;
use App\Entity\IncomeCategory;
use App\Service\TransactionsService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class IncomeController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/income/add-income', name: 'add_income', methods: 'POST')]
    public function addIncome(Request $request, EntityManagerInterface $em){
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $content = $request->getContent();
        $data = json_decode($content, true);

        $date = new \DateTime($data['date']);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        $categoryRepository = $em->getRepository(IncomeCategory::class);
        $category = $categoryRepository->findOneBy(["name" => $data["category"]["name"]]);

        $income = new Income();
        $income->setDate($date);
        $income->setName($data["name"]);
        $income->setAmount($data["amount"]);
        $income->setDetails($data["describe"]);
        $income->setCategory($category);
        $income->setAccount($account);
        $income->setIsDeleted(false);

        $em->persist($income);

        $account->setBalance($account->getBalance() + $data["amount"]);

        $history = new AccountHistory();
        $history->setDate($date);
        $history->setPreviousBalance($account->getBalance());
        $history->setUser($account->getUser());
        $history->setAccount($account);

        $em->persist($history);

        $em->flush();

        return new JsonResponse("Success saved income", 200);

    }
}
