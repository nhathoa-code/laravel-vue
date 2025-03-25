<?php

namespace App\Models\Administration;

use App\Models\Book\BookItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Library extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function bookItems(): HasMany
    {
        return $this->hasMany(BookItem::class,'current_location');
    }
}
