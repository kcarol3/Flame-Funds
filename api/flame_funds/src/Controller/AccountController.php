<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\User;
use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AccountController.php',
        ]);
    }

    /**
     * @throws NotSupported
     * @throws ORMException
     * @throws \Exception
     */
    #[Route('/account/create', name: 'create_account')]
    public function createAccount(Request $request, EntityManagerInterface $em): JsonResponse{
        $content = $request->getContent();
        $jsonData = json_decode($content, true);

        $userRepository = $em->getRepository(User::class);
        $user = $userRepository->findOneBy(["username" => $jsonData['username']]);


        if(!$user){
            return new JsonResponse("User does not exist", 500);
        }
        $newAccount = new Account();
        $newAccount->setUser($user);
        $newAccount->setBalance($jsonData['balance']);
        $currentDateTime = new DateTime();
        $newAccount->setCreatedDate($currentDateTime);
        $newAccount->setName($jsonData['name']);
        $newAccount->setIsDeleted(false);

        $em->persist($newAccount);
        $em->flush();

        return new JsonResponse("Success");
    }
}
