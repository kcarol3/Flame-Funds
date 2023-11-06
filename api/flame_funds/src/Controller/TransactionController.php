<?php

namespace App\Controller;

use App\Service\Strategy\Transaction;
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
class TransactionController extends AbstractController
{
    private EntityManagerInterface $em;
    private Transaction $transaction;

    public function __construct(EntityManagerInterface $em, Transaction $transaction)
    {
        $this->em = $em;
        $this->transaction = $transaction;
    }

    #[Route('/transaction/{type}/{id}', name: 'get_one_transaction', methods: 'GET')]
    public function getOneTransaction($type, $id): JsonResponse
    {
        $data = $this->transaction->getOneById($type, $id);
        return new JsonResponse($data, 200);
    }

    #[Route('/transaction/{type}/{id}', name: 'edit_transaction', methods: 'PUT')]
    public function editTransaction(Request $request, $type, $id){
        $content = $request->getContent();
        $newData = json_decode($content, true);

        $this->transaction->edit($type, $id, $newData);

        return new Response("Success edit", 200);
    }

    #[Route('/transaction/{type}', name: 'add_transaction', methods: 'POST')]
    public function addTransaction(Request $request, $type){
        $user = UserService::getUserFromToken($request, $this->em);

        $content = $request->getContent();
        $data = json_decode($content, true);

        $data['date'] = new \DateTime($data['date']);

        $this->transaction->add($type, $user, $data);

        return new JsonResponse("Success saved transaction", 200);
    }

    #[Route('/transaction/{type}/{id}', name: 'delete_transaction', methods: 'DELETE')]
    public function deleteTransaction($id, $type){
        $this->transaction->remove($type, $id);

        return new Response("Success deleted transaction", 200);
    }
}
