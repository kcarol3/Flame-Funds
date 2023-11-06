<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\AccountHistory;
use App\Entity\GoogleSheet;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Google_Service_Drive;
use Google_Service_Drive_Permission;
use Google_Service_Sheets;
use Google\Service\Sheets;
use Google\Service\Drive;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Google_Service_Sheets_Request;
use Google_Service_Sheets_ValueRange;


class GoogleSheetsService
{
    private $client;
    private $sheetsService;

    private $entityManager;

    public const TRANSACTION_SHEET_NAME = "Wydatki i przychody";
    public const ACCOUNT_HISTORY_SHEET_NAME = "Historia konta";


    /**
     * @throws \Google\Exception
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets and PHP');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS, Google_Service_Drive::DRIVE]);
        $client->setAccessType('offline');
        $client->setAuthConfig('../credentials.json');

        $this->sheetsService = new Google_Service_Sheets($client);
        $this->client = $client;
    }

    /**
     * Tworzy nowy arkusz z odpowiednimi uprawnieniami i przypisuje go do użytkownika, zwraca id arkusza.
     * @param String $title
     * @return string
     */
    public function createSpreadsheet(User $user, string $title, string $role, string $email)
    {
        $newPermission = new Google_Service_Drive_Permission();

        $newPermission->setType('user');
        $newPermission->setRole($role);
        $newPermission->setEmailAddress($email);

        $spreadsheet = new Sheets\Spreadsheet([
            'properties' => [
                'title' => $title,
            ],
        ]);
        $spreadsheet = $this->sheetsService->spreadsheets->create($spreadsheet);

        $service = new Google_Service_Drive($this->client);
        $service->permissions->create($spreadsheet->spreadsheetId, $newPermission);

        $newSpreadSheet = new GoogleSheet();
        $newSpreadSheet->setSpreadSheetId($spreadsheet->spreadsheetId);
        $newSpreadSheet->setSheetId(0);
        $newSpreadSheet->setSheetName(self::TRANSACTION_SHEET_NAME);
        $newSpreadSheet->setUser($user);
        $this->entityManager->persist($newSpreadSheet);

        $user->setSheetId($spreadsheet->spreadsheetId);
        $this->entityManager->flush();

        $this->changeSheetTitle($spreadsheet->spreadsheetId, 0, self::TRANSACTION_SHEET_NAME);
        $this->addTransactionsToSpreadsheet($user, $spreadsheet->spreadsheetId);

        $this->createSheet($user, $spreadsheet->spreadsheetId, self::ACCOUNT_HISTORY_SHEET_NAME);
        $this->addAccountHistoryDataToSpreadsheet($user, $spreadsheet->spreadsheetId);

        return $spreadsheet->spreadsheetId;
    }

    /**
     * Dodaje dane transakcji z bazy danych do arkusza użytkownika.
     * @param $spreadsheetId
     * @return Sheets\AppendValuesResponse|void
     */
    public function addTransactionsToSpreadsheet($user, $spreadsheetId)
    {
        //dodanie transakcji do arkusza
        $rows = $this->getTransactionsFromDatabase($user);
        $range = self::TRANSACTION_SHEET_NAME . '!A:G';

        return $this->appendNewRows($rows, $spreadsheetId, $range);
    }

    public function addAccountHistoryDataToSpreadsheet($user, $spreadsheetId)
    {
        $rows = $this->getAccountHistory($user);
        $sheet = $this->entityManager->getRepository(GoogleSheet::class)->findBy(['user' => $user, 'sheetName' => self::ACCOUNT_HISTORY_SHEET_NAME]);

        $range = self::ACCOUNT_HISTORY_SHEET_NAME . '!A:C';

        return $this->appendNewRows($rows, $spreadsheetId, $range);
    }

    public function changeSheetTitle(string $spreadsheetId,string $sheetId, string $title){
        try {
            $body = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
                [
                'requests' => [
                    'updateSheetProperties' => [
                        'properties' => [
                            'sheetId' => $sheetId,
                            'title' => $title
                            ],
                        'fields' => 'title'
                        ]
                    ]
                ]
            );
            return  $this->sheetsService->spreadsheets->batchUpdate($spreadsheetId,$body);
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }

    public function createSheet(User $user, string $spreadsheetId, string $title)
    {
        try {
            $body = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(array(
                'requests' => array(
                    'addSheet' => array(
                        'properties' => array(
                            'title' => $title
                        )
                    )
                )
            ));
            $result = $this->sheetsService->spreadsheets->batchUpdate($spreadsheetId,$body);

            $newGoogleSheet = new GoogleSheet();
            $newGoogleSheet->setSpreadSheetId($spreadsheetId);
            $newGoogleSheet->setSheetId($result->getReplies()[0]->getAddSheet()->getProperties()->getSheetId());
            $newGoogleSheet->setSheetName($title);
            $newGoogleSheet->setUser($user);

            $this->entityManager->persist($newGoogleSheet);
            $this->entityManager->flush();

            return $result;
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }


    public function appendNewRows(array $rows, string $spreadsheetId, string $range)
    {
        try {
            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $options = ['valueInputOption' => 'USER_ENTERED'];
            return $this->sheetsService->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }

    /**
     * Pobiera dane z arkusza.
     * @param $spreadsheetId
     * @return array[]
     */
    public function getDataFromSpreadsheet(string $spreadsheetId, string $range)
    {
        if ($spreadsheetId != "") {
            $response = $this->sheetsService->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        } else {
            return [];
        }
    }

    public function getAccountHistory(User $user)
    {
        $accHistoryRepo = $this->entityManager->getRepository(AccountHistory::class);
        $accountHistories = $user->getAccountHistories();

        if ($accountHistories) {
            $allAccountsData = [];
            foreach ($accountHistories as $key => $accountHistory) {
                $oneRow = [];
                $oneRow[] = $accountHistory->getAccount()->getName();
                $oneRow[] = $accountHistory->getDate()->format("Y-m-d H:i:s");
                $oneRow[] = $accountHistory->getPreviousBalance();
                $allAccountsData[] = $oneRow;
            }

            usort($allAccountsData, function ($a, $b) {
                return strtotime($a[1]) - strtotime($b[1]);
            });

            $titles = [
                "Nazwa konta",
                "Data",
                "Stan konta"
            ];

            array_unshift($allAccountsData, $titles);

            return $allAccountsData;

        } else {
            return null;
        }
    }

    /**
     * Pobiera dane transakcji z bazy danych.
     * @return array
     */
    public function getTransactionsFromDatabase(User $user)
    {
        $accountRepository = $this->entityManager->getRepository(Account::class);

        $accounts = $accountRepository->findBy(["user" => $user]);

        if (!$accounts) {
            return [];
        }
        $dataToReturn = [];

        foreach ($accounts as $account) {
            $incomes = $account->getIncomes();
            $expenses = $account->getExpenses();

            foreach ($incomes as $income) {
                $oneRow = [];
                $oneRow[] = $account->getName();
                $oneRow[] = 'Przychód';
                $oneRow[] = $income->getCategory()->getName();
                $oneRow[] = $income->getName();
                $oneRow[] = $income->getAmount();
                $oneRow[] = $income->getDate()->format("Y-m-d H:i:s");
                $oneRow[] = $income->getDetails();

                $dataToReturn[] = $oneRow;
            }

            foreach ($expenses as $expense) {
                $oneRow = [];
                $oneRow[] = $account->getName();
                $oneRow[] = 'Wydatek';
                $oneRow[] = $expense->getCategory()->getName();
                $oneRow[] = $expense->getName();
                $oneRow[] = $expense->getAmount();
                $oneRow[] = $expense->getDate()->format("Y-m-d H:i:s");
                $oneRow[] = $expense->getDetails();
                $dataToReturn[] = $oneRow;
            }
        }

        usort($dataToReturn, function ($a, $b) {
            return strtotime($a[5]) - strtotime($b[5]);
        });

        $titles = [
            "Nazwa konta",
            "Typ transakcji",
            "Kategoria",
            "Nazwa transakcji",
            "Kwota",
            "Data",
            "Szczegóły"
        ];

        array_unshift($dataToReturn, $titles);

        return $dataToReturn;
    }
}