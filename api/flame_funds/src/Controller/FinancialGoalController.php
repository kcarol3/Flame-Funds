<?php

namespace App\Controller;

use App\Entity\FinancialGoal;
use App\Entity\Account;
use App\Entity\AccountHistory;
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
class FinancialGoalController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Exception
     */
    #[Route('/financialGoal/add-financialGoal', name: 'add_financialGoal', methods: 'POST')]
    public function addFinancialGoal(Request $request, EntityManagerInterface $em){
        $user = UserService::getUserFromToken($request, $em);
        $accountId = $user->getCurrentAccount();

        $content = $request->getContent();
        $data = json_decode($content, true);

        $dateStart = new \DateTime($data['dateStart']);
        $dateEnd = new \DateTime($data['dateEnd']);

        $accountRepository = $em->getRepository(Account::class);
        $account = $accountRepository->findOneBy(["id" => $accountId]);

        $account = $accountRepository->findOneBy(["name" => $data["account"]["name"]]);

        $financialGoal = new FinancialGoal();
        $financialGoal->set($dateStart);
        $financialGoal->setDate($dateEnd);
        $financialGoal->setName($data["name"]);
        $financialGoal->setGoalAmount($data["goalAmount"]);
        $financialGoal->setCurrentAmount($data["currentAmount"]);
        $financialGoal->setDetails($data["describe"]);
        $financialGoal->setAccount($account);
        $financialGoal->setIsDeleted(false);

        $em->persist($financialGoal);

        $account->setBalance($account->getBalance() - $data["currentAmount"]);

        $history = new AccountHistory();
        $history->setDate($dateStart);
        $history->setPreviousBalance($account->getBalance());
        $history->setUser($account->getUser());
        $history->setAccount($account);

        $em->persist($history);

        $em->flush();

        return new JsonResponse("Success saved financial goal", 200);

    }
}
