<?php

namespace App\Models\Pos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashupSumary extends Model
{
    public function register(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class,'cash_register_id');
    }
}
