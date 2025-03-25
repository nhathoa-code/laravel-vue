<?php

namespace App\Http\Controllers;

use App\Models\BookItem;
use App\Models\Library;
use App\Models\Transfer;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    private $transferModel;

    public function __construct(Transfer $transferModel)
    {
        $this->transferModel = $transferModel;
    }

    public function index(Request $request)
    {
        $transfers = $this->transferModel->getTransfers(2);
        return $transfers;
    }

    public function transfer(Request $request,BookItem $bookItemModel)
    {
        $validated = $request->validate([
           'destination_library' => 'required|exists:libraries,id',
           'barcode' => 'required|exists:book_items' 
        ]);
        $bookItem = $bookItemModel->getBookItemByBarcode($validated['barcode']);
        $library = Library::find($validated['destination_library']);
        $this->transferModel->transfer($bookItem,$library);
        return response()->json([
            'message' => 'Item transfered successfully'
        ], 200);
    }

    public function receive(Request $request)
    {
        $validated = $request->validate([
           'transfer' => 'required|exists:transfers,id',
        ]);
        $this->transferModel->receive($validated['transfer']);
        return response()->json([
            'message' => 'Item received successfully'
        ], 200);
    }

    public function cancel(Request $request)
    {
        $validated = $request->validate([
           'transfer' => 'required|exists:transfers,id',
        ]);
        $this->transferModel->cancel($validated['transfer']);
        return response()->json([
            'message' => 'transfer cancelled successfully'
        ], 200);
    } 
}
