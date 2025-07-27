<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetService;
use App\Models\Item;

class FetchSheets extends Command
{
    protected $signature = 'sheet:fetch {count?}';
    protected $description = 'Импорт данных из Google Таблицы в базу данных';

    public function handle(): void
    {
        $spreadsheetId = config('services.google.spreadsheet_id');
        $sheetService = new GoogleSheetService($spreadsheetId);
        $sheetRows = $sheetService->fetchAllData();
        $limit = (int) ($this->argument('count') ?? count($sheetRows));
        $importRows = array_slice($sheetRows, 0, $limit);

        $progressBar = $this->output->createProgressBar(count($importRows));
        $progressBar->start();

        foreach ($importRows as $rowData) {
            [$itemId, $itemName,$itemDiscription] = array_pad($rowData, 3, null);

            if ($itemId && $itemName) {
                Item::updateOrCreate(
                    ['id' => $itemId],
                    ['name' => $itemName,'discription' => $itemDiscription,]
                );
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info('Импорт данных завершён успешно.');
    }
}
