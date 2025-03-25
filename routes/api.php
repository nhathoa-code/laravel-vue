<?php

use App\Http\Controllers\ILLRequestController;
use App\Http\Controllers\Acquisition\InvoiceController;
use App\Http\Controllers\TransferController;

use Illuminate\Support\Facades\Route;

require_once __DIR__ . '/Book/book.php';

require_once __DIR__ . '/Book/list.php';

require_once __DIR__ . '/User/user.php';

require_once __DIR__ . '/Acquisition/vendor.php';

require_once __DIR__ . '/Acquisition/budget.php';

require_once __DIR__ . '/Circulation/circulation.php';

require_once __DIR__ . '/Pos/pos.php';

require_once __DIR__ . '/Administration/administration.php';

Route::apiResource('invoices', InvoiceController::class);

Route::post('transfers',[TransferController::class,'transfer']);

Route::get('transfers',[TransferController::class,'index']);

Route::post('transfers/receive',[TransferController::class,'receive']);

Route::post('transfers/cancel',[TransferController::class,'cancel']);

Route::get('ill-requests/partners', [ILLRequestController::class,'searchPartners']);

Route::apiResource('ill-requests', ILLRequestController::class)->parameters(['ill-requests' => 'iLLRequest']);

Route::post('ill-requests/{iLLRequest}/confirm', [ILLRequestController::class,'confirmRequest']);