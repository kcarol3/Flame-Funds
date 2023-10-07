<?php

namespace App\Controller;

use App\Service\GoogleSheetsService;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api", name="api_")
 */
class GoogleSheetsController extends AbstractController
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Google\Exception
     */
    #[Route('/google/sheets', name: 'app_google_sheets', methods: 'POST')]
    public function createSheet(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $user = UserService::getUserFromToken($request, $em);

        $googleSheet = new GoogleSheetsService($em);

        $email = $data["email"] ?? $user->getEmail();

        $sheetID = $googleSheet->createSheet($user, $data["title"], $data["role"], $email);

        return new JsonResponse($sheetID, 200);
    }

    #[Route('/google/id', name: 'get_sheet_id', methods: 'GET')]
    public function getSheetId(Request $request, EntityManagerInterface $em):JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        return new JsonResponse($user->getSheetId(), 200);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Google\Exception
     */
    #[Route('/google/sheets', name: 'get_data_from_sheet', methods: 'GET')]
    public function getDataFromSheet(Request $request, EntityManagerInterface $em):JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);

        $googleSheet = new GoogleSheetsService($em);

        $sheetId = $user->getSheetId() ?? "";
        $transactions = $googleSheet->getTransactionsFromSheet($sheetId);

        return new JsonResponse($transactions, 200);
    }
}
