<?php

namespace App\Services;

use App\Models\Item;
use Google\Client;
use Google\Service\Sheets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class GoogleSheetService
{
    protected Client $googleClient;
    protected Sheets $sheetsService;
    protected string $spreadsheetId;

    public function __construct()
    {
        $this->googleClient = new Client();
        $this->googleClient->setApplicationName('Laravel Google Sheets');
        $this->googleClient->setScopes([Sheets::SPREADSHEETS]);
        $this->googleClient->setAuthConfig(config('services.google.credentials_path'));
        $this->googleClient->setAccessType('offline');

        $this->sheetsService = new Sheets($this->googleClient);
        $this->spreadsheetId = config('services.google.spreadsheet_id');
    }

    public function saveSheetUrl(Request $request)
    {
        $request->validate(['sheet_url' => 'required|url']);

        $sheetUrl = $request->input('sheet_url');
        preg_match('#/d/([a-zA-Z0-9-_]+)#', $sheetUrl, $matches);
        $extractedSpreadsheetId = $matches[1] ?? null;

        $envPath = base_path('.env');
        $envContent = file_get_contents($envPath);

        $envContent = preg_replace("/^GOOGLE_SHEET_URL=.*$/m", "GOOGLE_SHEET_URL={$sheetUrl}", $envContent);
        $envContent = preg_replace("/^GOOGLE_SPREADSHEET_ID=.*$/m", "GOOGLE_SPREADSHEET_ID={$extractedSpreadsheetId}", $envContent);

        file_put_contents($envPath, $envContent);

        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return back()->with('success', 'URL и ID таблицы сохранены');
    }

    public function fetchAllData(): array
    {
        $allRows = [];
        $batchSize = 500;
        $currentStartRow = 2;

        while (true) {
            $currentEndRow = $currentStartRow + $batchSize - 1;

            $range = "Лист1!A{$currentStartRow}:E{$currentEndRow}";

            $response = $this->sheetsService->spreadsheets_values->get($this->spreadsheetId, $range);
            $rows = $response->getValues();

            if (empty($rows)) {
                break;
            }

            $allRows = array_merge($allRows, $rows);
            $currentStartRow += $batchSize;

            if ($currentStartRow > 1000) {
                break;
            }
        }

        Log::info('Всего загружено строк: ' . count($allRows));

        return $allRows;
    }

    public function syncItemsToSheet(): void
    {
        $commentsMap = $this->getCommentsMap();
        $this->clearSheetRange();

        $items = Item::allowed()->get();

        $values = [['ID', 'Name', 'Discription', 'Status', 'Comment']];

        foreach ($items as $item) {
            $values[] = [
                $item->id,
                $item->name,
                $item->discription,
                $item->status,
                $commentsMap[$item->id] ?? '',
            ];
        }

        $body = new \Google\Service\Sheets\ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];
        $range = 'Лист1!A2:E';

        $this->sheetsService->spreadsheets_values->update($this->spreadsheetId, $range, $body, $params);
    }

    private function getCommentsMap(): array
    {
        $range = 'Лист1!A2:E';
        $response = $this->sheetsService->spreadsheets_values->get($this->spreadsheetId, $range);
        $rows = $response->getValues() ?? [];

        $comments = [];
        foreach ($rows as $row) {
            if (isset($row[0], $row[4])) {
                $comments[$row[0]] = $row[4];
            }
        }

        return $comments;
    }

    private function clearSheetRange(): void
    {
        $clearRequest = new \Google\Service\Sheets\ClearValuesRequest();
        $this->sheetsService->spreadsheets_values->clear($this->spreadsheetId, 'Лист1!A2:E', $clearRequest);
    }
}
