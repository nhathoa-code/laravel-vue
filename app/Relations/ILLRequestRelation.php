<?php

namespace App\Relations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BookItem;
use App\Models\Library;
use App\Models\Patron;

trait ILLRequestRelation
{
    public function bookItem(): BelongsTo
    {
        return $this->belongsTo(BookItem::class,'book_item_id');
    }

    public function patron(): BelongsTo
    {
        return $this->belongsTo(Patron::class,'patron');
    }

    public function lendingLib(): BelongsTo
    {
        return $this->belongsTo(Library::class,'lending_library');
    }

    public function borrowingLib(): BelongsTo
    {
        return $this->belongsTo(Library::class,'borrowing_library');
    }
}