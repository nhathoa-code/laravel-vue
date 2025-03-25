<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book\BList;
use App\Models\Book\Book;

class ListController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->lists()->with('items.book')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'owner' => 'required|exists:users,id',
            'type' => 'required|string|in:private,public',
            'sort_by' => 'required|string|in:title,author,date_added',
            'allow_changes_from' => 'required|integer|in:0,1,2'
        ]);
        $request->user()->lists()->create($validated);
        return response()->json([
            'message' => 'List created successfully'
        ], 201);
    }

    public function addItem(Request $request, BList $list, $book = null)
    {
        $validated = $request->validate([
            'book_id' => 'nullable|exists:books,id',
            'barcode' => 'nullable|exists:book_items'
        ]);
        if(isset($validated['book_id'])){
            $book = Book::find($validated['book_id']);
        }elseif(isset($validated['barcode'])){
            $book = Book::findByBarcode($validated['barcode']);
        }
        if($list->doesBookExists($book)){
            return response()->json([
                'message' => 'The book already exists in ' . $list->name . ' list'
            ], 400);
        }
        $list->addItem($book);
        return response()->json([
            'message' => 'Book added to ' . $list->name . ' list successfully'
        ], 201);
    }

    public function removeItem(BList $list, string $itemId)
    {
        $list->removeItem($itemId);
        return response()->json([
            'message' => 'Item removed from ' . $list->name . ' successfully'
        ], 200);
    }

    public function getItems(BList $bookList)
    {
        $Items = $bookList->getItems();
        return response()->json([
            'data' => $Items,
        ], 200);
    }

    public function show(BList $list)
    {
       return $list->load('items.book');
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
