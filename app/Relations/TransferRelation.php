<?php

namespace App\Relations;

use App\Models\BookItem;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Library;

trait TransferRelation
{
    public function toLibrary(): BelongsTo
    {
        return $this->belongsTo(Library::class,'to');
    }

    public function fromLibrary(): BelongsTo
    {
        return $this->belongsTo(Library::class,'from');
    }

    public function bookItem(): BelongsTo
    {
        return $this->belongsTo(BookItem::class,'book_item');
    }
}