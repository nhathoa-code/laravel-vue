<?php

namespace App\Models\Book;

use App\Relations\BListRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BList extends Model
{
    use BListRelation;

    protected $table = 'lists';
    protected $guarded = [];
    public $timestamps = false;

    public function addItem(Book $book)
    {
        $this->items()->create([
            'book_id' => $book->id,
            'added_on' => Carbon::now()->toDateString()
        ]);
    }

    public function removeItem(string|int $itemId)
    {
        $this->items()->where('id',$itemId)
            ->limit(1)
            ->delete();
    }

    public function doesBookExists(Book $book)
    {
        return $this->items()->where('book_id',$book->id)->exists();
    }

    public function getItems()
    {
        $this->load('bookItems.book','bookItems.library');
        $Items = $this->bookItems;
        return $Items;
    }

    public function getById(string $id)
    {
        return self::findOrFail($id);
    }

    public function countItems()
    {
        return DB::table('list_items')
            ->where("list_id",$this->id)->count();
    }
}
