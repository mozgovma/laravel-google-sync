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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
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
