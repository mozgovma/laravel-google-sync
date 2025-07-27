<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleSheetService;

class SyncSheet extends Command
{
    protected $signature = 'sync:google-sheet';
    protected $description = 'Sync allowed items with Google Sheets';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(GoogleSheetService $googleSheetService)
    {
        $this->info('Синхронизация с Google Таблицей...');
        $googleSheetService->syncItemsToSheet();
        $this->info('Данные успешно синхронизированы!');
    }
}