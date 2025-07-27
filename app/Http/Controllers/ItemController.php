<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\ItemService;
use Illuminate\Http\Request;


class ItemController extends Controller
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index()
    {
        $items = Item::query()->paginate(10);
        return view('items.index', compact('items'));
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Запись удалена.');
    }

    public function toggleStatus(Item $item)
    {
        $item->status = $item->status === 'Allowed' ? 'Prohibited' : 'Allowed';
        $item->save();

        return redirect()->back()->with('success', 'Статус обновлён.');
    }
    public function clear(Item $item)
    {
        $this->itemService->clearTable();

        return redirect()->route('home')->with('success','Таблица успешно отчищена');
    }

    public function generateFakeData()
    {
        $this->itemService->generateFakeData();

        return redirect()->route('home')->with('success','1000 записей сгенерированы');
    }
}
