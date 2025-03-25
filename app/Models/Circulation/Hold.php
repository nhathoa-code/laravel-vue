<?php

namespace App\Models\Circulation;

use App\Models\Administration\Library;
use App\Models\Book\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hold extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function pickupLocation(): BelongsTo
    {
        return $this->belongsTo(Library::class,'pickup_library');
    }
}
