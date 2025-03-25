<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Relations\BookRelation;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    use HasFactory;
    use BookRelation;

    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'authors' => 'array'
    ];

    public function storeBook(array $data,Request $request)
    {
        $cover_image = $request->file('cover_image');
        $originalName = $cover_image->getClientOriginalName();
        $data['cover_image'] = $cover_image->storeAs(now()->format('Y/m'),$originalName,'public');
        $data['language'] = 'vi';
        $book = $this->create($data);
        return $book;
    }

    public function updateBook(array $data,Request $request)
    {
        if($request->hasFile('cover_image')){
            $cover_image = $request->file('cover_image');
            $originalName = $cover_image->getClientOriginalName();
            $data['cover_image'] = $cover_image->storeAs(now()->format('Y/m'),$originalName,'public');
        }
        $categories = $data['categories'] ?? []; unset($data['categories']);
        $this->where('id',$this->id)->update($data);
        $this->updateCategories($categories);
    }

    public function storeItem(array $data)
    {
        $data['date_acquired'] = now()->toDateString();
        $data['current_location'] = $data['home_library'];
        $Item = $this->items()->create($data);
        $Item->load('homeLibrary','currentLocation','location');
        return $Item;
    }

    public function updateItem(string|int $itemId, array $data)
    {
        $bookItem = $this->items()->findOrFail($itemId);
        $bookItem->update([
            'barcode' => $data['barcode'],
            'shelving_location' => $data['shelving_location']
        ]);
        $bookItem->load('location');
        return $bookItem;
    }

    public function destroyItem(string|int $bookItemId)
    {
        $this->items()->where('id',$bookItemId)
            ->limit(1)
            ->delete();
    }

    public static function findByBarcode(string $barcode)
    {
        $bookItem = BookItem::where('barcode',$barcode)->firstOrFail();
        return $bookItem->book;
    }

    public function getAvailableItems(int $currentLocation)
    {
        $query = BookItem::where('book',$this->id)
                    ->where('status','available');
        if($currentLocation){
            $query->where('current_location',$currentLocation);
        }
        return $query->get();
    }

    protected function storeCategories(array $categories)
    {
        foreach($categories as $cat_id){
            DB::table("book_category")->insert([
                "book_id" => $this->id,
                "category_id" => $cat_id
            ]);
        }
    }

    protected function updateCategories(array $categories)
    {
        $categories_from_db = $this->loadCategories()->map(function($item){
            return $item->category_id;
        })->toArray();
        $full_categories_diff = array_merge(
                array_diff($categories_from_db,$categories),
                array_diff($categories,$categories_from_db)
            );
        foreach($full_categories_diff as $item){
            if(in_array($item,$categories_from_db)){
                DB::table("book_category")->where("book_id", $this->id)
                    ->where("category_id",$item)->delete();
            }else{
                DB::table("book_category")->insert([
                    "book_id" => $this->id,
                    "category_id" => $item
                ]);
            }
        }
    }

    public function loadCategories()
    {
        $categories = DB::table('book_category')
            ->where('book_id',$this->id)
            ->get();
        $this->categories = $categories;
        return $this->categories;
    }

    public function search(string $search_by,string $q)
    {
        switch($search_by){
            case 'title':
            case 'author':
                return $this->whereFullText(['title','authors'], $q)->get();
                break;
            case 'isbn':
                return $this->where('isbn','like','%'.$q.'%')->get();
                break;
            case 'publisher':
                return $this->where('publisher','like','%'.$q.'%')->get();
                break;
        }
    } 

    protected function coverImage(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => asset("storage/" . $value),
        );
    }
}
