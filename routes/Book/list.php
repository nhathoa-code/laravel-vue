<?php

use App\Http\Controllers\Book\ListController;
use Illuminate\Support\Facades\Route;

Route::post('lists/{list}/items', [ListController::class, 'addItem']);

Route::delete('lists/{list}/items/{itemId}', [ListController::class, 'removeItem']);

Route::apiResource('lists', ListController::class);

Route::get('lists/{bookList}/items', [ListController::class, 'getItems']);