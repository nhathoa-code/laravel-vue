<?php

namespace App\Relations;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Pos\CashupSumary;
use App\Models\User\User;

trait CashupHistoryRelation
{
    public function cashupSumary(): HasMany
    {
        return $this->hasMany(CashupSumary::class,'cashup_history_id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class,'operator');
    }
}