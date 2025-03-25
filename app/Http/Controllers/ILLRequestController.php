<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\ILLRequest;
use App\Models\Patron;
use Illuminate\Http\Request;

class ILLRequestController extends Controller
{
    private $ILLRequestModel;

    public function __construct(ILLRequest $ILLRequestModel)
    {
        $this->ILLRequestModel = $ILLRequestModel;
    }

    public function index()
    {
        return ILLRequest::with(
            'bookItem.book',
            'patron',
            'lendingLib',
            'borrowingLib')
        ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'lending_library' => 'required|exists:libraries,id',
            'borrowing_library' => 'required|exists:libraries,id',
            'patron' => 'required|exists:patrons,id'
        ]);
        $book = Book::find($validated['book_id']);
        $availableItems = $book->getAvailableItems($validated['lending_library']);
        if(!$availableItems->isEmpty()){
            $bookItem = $availableItems->random();
        }
        $validated['book_item'] = $bookItem->id;
        $request = $this->ILLRequestModel->storeRequest($validated);   
        return response()->json([
            'request' => $request
        ], 200);
    }

    public function show(ILLRequest $iLLRequest)
    {
        $iLLRequest->load(
            'bookItem.book',
            'patron',
            'borrowingLib',
            'lendingLib'
        );
        return $iLLRequest;
    }

    public function update(Request $request, ILLRequest $iLLRequest)
    {
        
    }

    public function destroy(ILLRequest $iLLRequest)
    {
        $iLLRequest->delete();
    }

    public function searchPartners(Request $request,Patron $patronModel)
    {
        $validated = $request->validate([
            'keyword' => 'required|string',
            'card_number' => 'required|exists:patrons',
            'pickup_library' => 'required|exists:libraries,id'
        ]);
        $patron = $patronModel->getByCard($validated['card_number']);
        $partners = $this->ILLRequestModel->searchPartners($validated['keyword']);
        return response()->json([
            'partners' => $partners,
            'patron' => $patron
        ], 200);
    }

    public function confirmRequest(Request $request, ILLRequest $iLLRequest)
    {
        $validated = $request->validate([
            'patron' => 'required|exists:patrons,id',
            'book' => 'required|exists:books,id',
            'book_item' => 'required|exists:book_items,id',
            'pickup_library' => 'required|exists:libraries,id'
        ]);
        $patron = Patron::find($validated['patron']);
        $patron->placeHold($validated);
        $iLLRequest->updateStatus('requested');
        return response()->json([
            'message' => 'Request confirmed successfully'
        ], 200);
    }
}
