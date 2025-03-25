<?php

use App\Http\Controllers\Administration\LibraryController;
use App\Http\Controllers\Administration\CategoryController;
use App\Http\Controllers\Administration\AuthorizedCategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class);

Route::apiResource('libraries', LibraryController::class);

Route::apiResource('authorized-categories', AuthorizedCategoryController::class)->parameters(['authorized-categories' => 'authorized_value']);