<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Book\Book;


class ListItem extends Model
{
    protected $table = 'list_items';
    protected $guarded = [];
    public $timestamps = false;

    protected $casts = [
        'added_on' => 'datetime:d-m-Y',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class,'book_id');
    }

}
