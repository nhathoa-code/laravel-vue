<?php

use App\Http\Controllers\Acquisition\VendorController;
use Illuminate\Support\Facades\Route;

Route::apiResource('vendors', VendorController::class);

Route::post('vendors/{vendor}/baskets', [VendorController::class,'addBasket']);

Route::get('vendors/{vendor}/baskets/{basketId}', [VendorController::class,'showBasket']);

Route::put('vendors/{vendor}/baskets/{basketId}', [VendorController::class,'updateBasket']);

Route::delete('vendors/{vendor}/baskets/{basketId}', [VendorController::class,'deleteBasket']);

Route::post('vendors/{vendor}/baskets/{basketId}/additem', [VendorController::class,'addItem']);

Route::post('vendors/{vendor}/baskets/{basketId}/deleteitem', [VendorController::class,'deleteItem']);

Route::get('vendors/{vendor}/spending-orders', [VendorController::class,'spendingOrders']);

Route::post('vendors/{vendor}/invoices', [VendorController::class,'invoice']);

Route::get('vendors/{vendor}/baskets', [VendorController::class,'showBaskets']);