<?php

namespace App\Models\Acquisition;

use App\Models\Book\Book;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasketItem extends Model
{
    public $table = 'basket_items';
    public $timestamps = false;
    protected $guarded = [];

    public function fund(): BelongsTo
    {
        return $this->belongsTo(BudgetFund::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }

    protected function casts(): array
    {
        return [
            'creation_date' => 'datetime:d-m-Y'
        ];
    }
}
