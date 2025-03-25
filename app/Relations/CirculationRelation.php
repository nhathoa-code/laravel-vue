<?php

namespace App\Relations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Book\BookItem;
use App\Models\User\User;

trait CirculationRelation
{
    public function bookItem(): BelongsTo
    {
        return $this->belongsTo(BookItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}