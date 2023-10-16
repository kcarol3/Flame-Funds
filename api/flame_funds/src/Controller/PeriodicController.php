<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\Periodic;
use App\Entity\ExpenseCategory;
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
class PeriodicController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/periodic/add-periodic', name: 'add_periodic', methods: 'POST')]
    public function addPeriodic(Request $request, EntityManagerInterface $em){
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $content = $request->getContent();
        $data = json_decode($content, true);

        $dateStart = new \DateTime($data['dateStart']);
        $dateEnd = new \DateTime($data['dateEnd']);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        $categoryRepository = $em->getRepository(ExpenseCategory::class);
        $category = $categoryRepository->findOneBy(["name" => $data["category"]["name"]]);

        $periodic = new Periodic();
        $periodic->setName($data["name"]);
        $periodic->setAmount($data["amount"]);
        $periodic->setDateStart($dateStart);
        $periodic->setDateEnd($dateEnd);
        $periodic->setDetails($data["details"]);
        $periodic->setDays($data["days"]);
        $periodic->setCategory($category);
        $periodic->setAccount($account);
        $periodic->setIsDeleted(false);

        $em->persist($periodic);

        $account->setBalance($account->getBalance() - $data["amount"]);

        $history = new AccountHistory();
        $history->setDate($dateStart);
        $history->setPreviousBalance($account->getBalance());
        $history->setUser($account->getUser());
        $history->setAccount($account);

        $em->persist($history);

        $em->flush();

        return new JsonResponse("Success saved periodic", 200);

    }
}
