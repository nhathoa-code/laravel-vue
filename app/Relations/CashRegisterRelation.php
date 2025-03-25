<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Administration\Library;
use App\Models\Pos\CashupHistory;
use App\Models\Pos\Transaction;

trait CashRegisterRelation
{
    public function toLibrary(): BelongsTo
    {
        return $this->belongsTo(Library::class,'library_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class,'cash_register_id');
    }

    public function cashUpHistories(): HasMany
    {
        return $this->hasMany(CashupHistory::class,'cash_register_id');
    }
}