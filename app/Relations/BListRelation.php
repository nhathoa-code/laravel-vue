<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Book\ListItem;

trait BListRelation
{
    public function items(): HasMany
    {
        return $this->hasMany(ListItem::class,'list_id');
    }
}