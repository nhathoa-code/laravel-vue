<?php

use App\Http\Controllers\Pos\PosController;
use App\Http\Controllers\Pos\CashRegisterController;
use App\Http\Controllers\Pos\DebitTypeController;
use Illuminate\Support\Facades\Route;

Route::post('pos/transaction',[PosController::class,'transaction']);

Route::post('pos/{cashRegister}/cashup', [PosController::class,'registerCashup']);

Route::post('pos/refund/{item}', [PosController::class,'refundItem']);

Route::apiResource('cash-registers', CashRegisterController::class);

Route::apiResource('debit-types', DebitTypeController::class);