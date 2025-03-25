<?php

namespace App\Relations;

use App\Models\Book\BookItem;
use App\Models\Acquisition\InvoiceItem;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait BookRelation
{
    public function items(): HasMany
    {
        return $this->hasMany(BookItem::class,'book_id');
    }

    public function acquisitionDetails(): HasMany
    {
        return $this->hasMany(InvoiceItem::class,'book_id');
    }
}