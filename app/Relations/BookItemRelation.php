<?php

namespace App\Relations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Administration\AuthorizedValue;
use App\Models\Administration\Library;
use App\Models\Book\Book;
use App\Models\Book\BList;

trait BookItemRelation
{
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function homeLibrary(): BelongsTo
    {
        return $this->belongsTo(Library::class,'home_library');
    }

    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(Library::class,'current_location');
    }

    public function bookList(): BelongsTo
    {
        return $this->belongsTo(BList::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(AuthorizedValue::class,'shelving_location');
    }
}