<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::get('users/card', [UserController::class,'showByCard']);

Route::post('users/{user}/books/{book}/reserve', [UserController::class,'reserve']);

Route::post('users/{user}/changepassword', [UserController::class,'changePassword']);

Route::get('user', [UserController::class,'getLoggedInUser'])->middleware('auth:sanctum');

Route::apiResource('users', UserController::class);