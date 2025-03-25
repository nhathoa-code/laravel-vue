<?php

use App\Http\Controllers\Circulation\CirculationController;
use Illuminate\Support\Facades\Route;

Route::post('circulations/checkout', [CirculationController::class, 'checkout']);

Route::post('circulations/checkin', [CirculationController::class, 'checkin']);

Route::post('circulations/renew', [CirculationController::class, 'renew']);

Route::get('circulations/overdues', [CirculationController::class, 'overdues']);

Route::post('circulations/rules', [CirculationController::class, 'defineRules']);

Route::post('circulations/patron/{patron}/place-hold', [CirculationController::class,'placeHold']);

Route::get('options',[CirculationController::class,'options']);