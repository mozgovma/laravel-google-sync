<?php

namespace App\Http\Controllers;

use App\Services\GoogleSheetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GoogleSheetController extends Controller
{

    protected $googleSheetService;

    public function __construct(GoogleSheetService $googleSheetService)
    {
        $this->googleSheetService = $googleSheetService;
    }

    public function fetch($count = null)
    {

        $projectRoot = base_path();
        $command = 'php ' . $projectRoot . '/artisan sheet:fetch';
        
        if ($count) {
            $command .= ' ' . $count;
        }

        $output = shell_exec($command);


        if (empty($output)) {
            Log::error('Команда не вернула вывод или произошла ошибка');
            return "Ошибка при выполнении команды.";
        }

        return nl2br($output);
    }

    public function saveOrEdit(Request $request)
    {
        $this->googleSheetService->saveSheetUrl($request);

        return redirect()->route('home')->with('success', 'Ссылка успешно установлена');
    }
}
