<?php

use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Book\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('books/search', [BookController::class,'search']);

Route::get('books/searchitems', SearchController::class);

Route::apiResource('books', BookController::class);

Route::post('books/{book}/items',[BookController::class,'storeItem']);

Route::put('books/{book}/items/{itemId}',[BookController::class,'updateItem']);

Route::delete('books/{book}/items/{itemId}',[BookController::class,'destroyItem']);