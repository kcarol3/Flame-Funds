<?php

namespace App\Service;

use App\Entity\Account;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Google_Service_Drive;
use Google_Service_Drive_Permission;
use Google_Service_Sheets;
use Google\Service\Sheets;
use Google\Service\Drive;
use Google_Service_Sheets_ValueRange;


class GoogleSheetsService
{
    private $client;
    private $service_Sheets;

    private $entityManager;

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

        $this->service_Sheets = new Google_Service_Sheets($client);
        $this->client = $client;
    }

    /**
     * Tworzy nowy arkusz z odpowiednimi uprawnieniami i przypisuje go do użytkownika, zwraca id arkusza.
     * @param String $title
     * @return string
     */
    public function createSheet(User $user, string $title, string $role, string $email)
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
        $spreadsheet = $this->service_Sheets->spreadsheets->create($spreadsheet);

        $service = new Google_Service_Drive($this->client);
        $service->permissions->create($spreadsheet->spreadsheetId, $newPermission);

        $user->setSheetId($spreadsheet->spreadsheetId);
        $this->entityManager->flush();

        $this->addTransactionsToSpreadsheet($user, $spreadsheet->spreadsheetId);

        return $spreadsheet->spreadsheetId;
    }

    /**
     * Dodaje dane transakcji z bazy danych do arkusza użytkownika.
     * @param $spreadsheetId
     * @return Sheets\AppendValuesResponse|void
     */
    public function addTransactionsToSpreadsheet($user, $spreadsheetId)
    {
        try{
            $rows = $this->getTransactionsFromDatabase($user);

            $valueRange = new \Google_Service_Sheets_ValueRange();
            $valueRange->setValues($rows);
            $range = 'Sheet1'; // the service will detect the last row of this sheet
            $options = ['valueInputOption' => 'USER_ENTERED'];

            $result = $this->service_Sheets->spreadsheets_values->append($spreadsheetId, $range, $valueRange, $options);

            return $result;
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }

    /**
     * Pobiera dane z arkusza.
     * @param $spreadsheetId
     * @return array[]
     */
    public function getTransactionsFromSheet($spreadsheetId){
        if($spreadsheetId != ""){
            $range = 'Sheet1';
            $response = $this->service_Sheets->spreadsheets_values->get($spreadsheetId, $range);
            return $response->getValues();
        }
        else {
            return [];
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

        if(!$accounts){
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
            return strtotime($b[5]) - strtotime($a[5]);
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