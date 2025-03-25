<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Book\Book;
use App\Models\Book\BookItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    private $bookModel;

    public function __construct(Book $bookModel)
    {
        $this->bookModel = $bookModel;
    }

    public function index(Request $request)
    {
        $books = Book::paginate(2);
        if($request->query('getLocations') == 'true'){
            $items = BookItem::from('book_items as bi')
                ->join('libraries as lib','lib.id','=','bi.current_location')
                ->leftJoin('loans as l','l.book_item_id','=','bi.id')
                ->select(
                    DB::raw('COUNT(case when l.id is null then 1 end) as available'),
                    DB::raw(
                    'COUNT(case when l.id is not null then 1 end) as checked_out'),
                    'lib.name','bi.book_id'
                )
                ->groupBy('lib.name','bi.book_id')
                ->whereIn('bi.book_id',$books->pluck('id'))
                ->get();
            $books->each(function($b) use ($items){
                $b->locations = $items->filter(function($item) use ($b){
                    return $item->book_id == $b->id;
                });
            });
        }
        return $books;
    }

    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();
        $categories = $validated['categories']; unset($validated['categories']);
        $book = $this->bookModel->storeBook($validated,$request);
        $book->storeCategories($categories);
        return response()->json([
            'message' => 'Catalog created successfully'
        ], 201);
    }

    public function show(Request $request,Book $book)
    {
        $relations = [
            'acquisitionDetails.basket.vendor',
            'acquisitionDetails.invoice'
        ];
        if($request->query('getItems') == 'true'){
            array_push($relations,'items');
            array_push($relations,'items.homeLibrary');
            array_push($relations,'items.currentLocation');
            array_push($relations,'items.location');
        }
        $book->load($relations);
        $book->loadCategories();
        return $book;
    }

    public function update(StoreBookRequest $request, string $id)
    {
        $validated = $request->validated();
        $book = Book::find($id);
        $book->updateBook($validated,$request);
        return response()->json([
            'message' => 'Catalog updated successfully'
        ], 200);
    }

    public function destroy(string $id)
    {
        $book = Book::find($id);
        $book->delete();
    }

    public function storeItem(Request $request,Book $book)
    {
        $validated = $request->validate([
            'barcode' => 'bail|required|string|max:50|unique:book_items',
            'home_library' => 'bail|required|exists:libraries,id',
            'shelving_location' => 'nullable|exists:authorized_values,id'
        ]);
        $bookItem = $book->storeItem($validated);
        return response()->json([
            'data' => $bookItem,
            'message' => 'Item created successfully'
        ], 201);
    }

    public function updateItem(Request $request, Book $book, string $itemId)
    {
        $validated = $request->validate([
            'barcode' => 'bail|required|string|max:50|unique:book_items,barcode,' . $itemId,
            'shelving_location' => 'nullable|exists:authorized_values,id'
        ]);
        $bookItem = $book->updateItem($itemId,$validated);
        return response()->json([
            'data' => $bookItem,
            'message' => 'Book item updated successfully'
        ], 200);
    }

    public function destroyItem(Book $book, string $itemId)
    {
        $book->destroyItem($itemId);
        return response()->json([
            'message' => 'Book item deleted successfully'
        ], 200);
    }

    public function search(Request $request)
    {
        $search_type = $request->query('search_type');
        $query = $request->query('q');
        $results = $this->bookModel->search($search_type,$query);
        return $results;
    }
}
