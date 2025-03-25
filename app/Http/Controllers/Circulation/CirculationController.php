<?php

namespace App\Http\Controllers\Circulation;

use App\Http\Controllers\Controller;
use App\Http\Requests\defineRulesRequest;
use Illuminate\Http\Request;
use App\Models\Circulation\Circulation;
use App\Models\Book\BookItem;
use App\Models\Commons\Option;
use App\Models\User\User;

class CirculationController extends Controller
{
    private $circulationModel;

    public function __construct(Circulation $circulationModel)
    {
        $this->circulationModel = $circulationModel;
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|exists:book_items',
            'patron_id' => 'required|exists:users,id'
        ]);
        $bookItem = BookItem::findByBarcode($validated['barcode']);
        $isCheckedOut = $this->circulationModel->isCheckedOut($bookItem);
        if($isCheckedOut){
            return response()->json([
                'message' => 'Book item not available'
            ], 400);
        }
        $patron = User::find($validated['patron_id']);
        if($patron->countHolds() > 10){
            return response()->json([
                'message' => 'The patron has exceeded limitation of circulations'
            ], 400);
        }
        $this->circulationModel->checkout($patron,$bookItem);
        $bookItem->increaseCheckouts()->updateLastSeen()->updateStatus('borrowed');
        $circulation = $this->circulationModel->getCirculation($bookItem);
        $circulation->load(
            'bookItem.book',
            'user.loans.bookItem.book',
            'user.loans.bookItem.homeLibrary'
        );
        return response()->json([
            'circulation' => $circulation,
            'message' => 'checked out successfully'
        ], 201);
    }

    public function checkin(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|exists:book_items',
        ]);
        $bookItem = BookItem::findByBarcode($validated['barcode']);
        $isCheckedOut = $this->circulationModel->isCheckedOut($bookItem);
        if(!$isCheckedOut){
            return response()->json([
                'message' => 'Book item not currently checked out'
            ], 400);
        }
        $bookItem->updateLastSeen()->updateStatus('available');
        $circulation = $this->circulationModel->getCirculation($bookItem);
        $circulation->load('user.userMeta','bookItem.book');
        $this->circulationModel->checkin($bookItem);
        return response()->json([
            'circulation' => $circulation,
            'message' => 'checked in successfully'
        ], 200);
    }

    public function renew(Request $request)
    {
        $validated = $request->validate([
            'barcode' => 'required|exists:book_items',
            'due_date' => 'nullable|date|after_or_equal:' . now()->toDateString()
        ]);
        $bookItem = BookItem::findByBarcode($validated['barcode']);
        $isCheckedOut = $this->circulationModel->isCheckedOut($bookItem);
        if(!$isCheckedOut){
            return response()->json([
                'message' => 'Book item not currently checked out'
            ], 400);
        }
        $this->circulationModel->renew($bookItem,$validated['due_date'] ?? null);
        $bookItem->increaseRenewals()->updateLastSeen();
        $circulation = $this->circulationModel->getCirculation($bookItem);
        $circulation->load('user.userMeta','bookItem.book');
        return response()->json([
            'circulation' => $circulation,
            'message' => 'Item renewed successfully'
        ], 200);
    }

    public function overdues()
    {
        $overdues = $this->circulationModel->getOverdues();
        return $overdues;
    }

    public function placeHold(Request $request,User $patron)
    {
        $validated = $request->validate([
            'book' => 'required|exists:books,id',
            'pickup_library' => 'required|exists:libraries,id'
        ]);
        $patron->placeHold($validated);
        return response()->json([
            'message' => 'Book held successfully'
        ], 200);
    }

    public function defineRules(defineRulesRequest $request)
    {
        $validated = $request->validated();
        $this->circulationModel->defineRules($validated);
        return response()->json([
            'message' => 'circulation fine rules defined successfully'
        ], 200);
    }

    public function options(Request $request)
    {
        $key = $request->get('key');
        return Option::get($key);
    }
}
