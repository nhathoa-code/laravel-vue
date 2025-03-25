<?php

use App\Http\Controllers\Acquisition\BudgetController;
use Illuminate\Support\Facades\Route;

Route::apiResource('budgets', BudgetController::class);

Route::get('funds', [BudgetController::class,'funds']);

Route::post('budgets/{budget}/funds', [BudgetController::class,'addFund']);

Route::get('budgets/{budget}/funds/{fundId}', [BudgetController::class,'fund']);

Route::put('budgets/{budget}/funds/{fundId}', [BudgetController::class,'updateFund']);

Route::delete('budgets/{budget}/funds/{fundId}', [BudgetController::class,'deleteFund']);