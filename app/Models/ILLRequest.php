<?php

namespace App\Models;

use App\Relations\ILLRequestRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ILLRequest extends Model
{
    use ILLRequestRelation;

    protected $table = 'ill_requests';
    protected $guarded = [];

    public function storeRequest(array $data)
    {
        unset($data['book_id']);
        return self::create($data);
    }

    public function searchPartners(string $keyword)
    {
        return Book::join('book_items as bi','books.id','=','bi.book')
            ->leftJoin('loans as l','l.book_item_id','=','bi.id')
            ->leftJoin('libraries as lib','lib.id','=','bi.current_location')
            ->select(
                'books.id as book_id',
                'books.title as title',
                'books.author',
                'books.isbn',
                'bi.current_location',
                'lib.name as library',
                DB::raw('COUNT(bi.id) as total_items'),
                DB::raw('SUM(CASE WHEN l.book_item_id IS NULL THEN 0 ELSE 1 END) as total_checkout')
            )
            ->whereFullText(['books.title','books.author'], $keyword)
            ->orWhere('books.isbn',$keyword)
            ->groupBy('bi.current_location','lib.name','books.title','books.isbn','books.author','books.id')
            ->havingRaw('CAST(total_items AS UNSIGNED) > CAST(total_checkout AS UNSIGNED)')
            ->get();
    }

    public function updateStatus(string $status)
    {
        $this->status = $status;
        $this->save();
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d-m-Y H:i',
            'updated_at' => 'datetime:d-m-Y H:i',
        ];
    }
}
