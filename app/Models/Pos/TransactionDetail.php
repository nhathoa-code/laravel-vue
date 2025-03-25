<?php

namespace App\Models\Pos;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    public $timestamps = false;

    public function tran(): BelongsTo
    {
        return $this->belongsTo(Transaction::class,'transaction_id');
    }

    public function markAsRefunded()
    {
        $this->refunded = true;
        $this->save();
    }
}
