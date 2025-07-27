<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\GoogleSheetController;

Route::get('/',[ItemController::class,'index'])->name('home');

Route::post('/items/generate', [ItemController::class, 'generateFakeData'])->name('items.generate');

Route::get('/items/clear', [ItemController::class, 'clear'])->name('items.clear');

Route::post('/save-or-edit', [GoogleSheetController::class, 'saveOrEdit'])->name('save-or-edit');

Route::get('/fetch/{count?}', [GoogleSheetController::class, 'fetch']);