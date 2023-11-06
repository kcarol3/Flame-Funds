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
    private $em;

    private GoogleSheetsService $sheetsService;

    public function __construct(EntityManagerInterface $em, GoogleSheetsService $sheetsService)
    {
        $this->em = $em;
        $this->sheetsService = $sheetsService;
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Google\Exception
     */
    #[Route('/google/sheets', name: 'app_google_sheets', methods: 'POST')]
    public function createSheet(Request $request): JsonResponse
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $user = UserService::getUserFromToken($request, $this->em);

        $email = $data["defaultEmail"] ? $user->getEmail() : $data['email'];

        $sheetID = $this->sheetsService->createSpreadsheet($user, $data["title"], $data["role"], $email);

        return new JsonResponse(['sheetId' => $sheetID], 200);
    }

    #[Route('/google/id', name: 'get_sheet_id', methods: 'GET')]
    public function getSheetId(Request $request, EntityManagerInterface $em):JsonResponse
    {
        $user = UserService::getUserFromToken($request, $em);
        $sheetId = $user->getSheetId();

        if($sheetId){
            return new JsonResponse(["sheetId" => $user->getSheetId() ], 200);
        } else {
            return new JsonResponse(["sheetId" => null], 200);
        }

    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     * @throws \Google\Exception
     */
    #[Route('/google/sheets', name: 'get_data_from_sheet', methods: 'GET')]
    public function getDataFromSheet(Request $request):JsonResponse
    {
        $user = UserService::getUserFromToken($request, $this->em);
        $sheetId = $user->getSheetId() ?? "";

//        $transactions = $this->sheetsService->getTransactionsFromSheet($sheetId);
        $rows = $this->sheetsService->getAccountHistory($user);
        //$transactions = $this->sheetsService->appendNewRows($rows, $user->getSheetId(), 'Sheet2');

        $transactions = $this->sheetsService->createSheet($user->getSheetId(), "test");
//        $transactions = $this->sheetsService->changeSheetTitle($sheetId, '0', GoogleSheetsService::TRANSACTION_SHEET_NAME);
        return new JsonResponse($transactions, 200);
    }
}
