<?php

namespace App\Models\Pos;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use App\Models\User\User;

class Transaction extends Model
{
    public $timestamps = false;
    protected $guarded = [];

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class,'transaction_id');
    }

    public function register(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class,'cash_register_id');
    }

    public function tranOperator(): BelongsTo
    {
        return $this->belongsTo(User::class,'operator');
    }

    public function culculateTotal(Collection $details)
    {
        $total = 0;
        $details->each(function($item) use(&$total){
            $total += $item->quantity * $item->price;
        });
        return $total;
    }

    public function insertDetails(array $debitTypes)
    {
        foreach($debitTypes as $item){
            $item['transaction_id'] = $this->id;
            TransactionDetail::insert($item);
        }
    }

    public function calculateTotalFromDebitTypes(array $debitTypes)
    {
        $total = 0;
        foreach($debitTypes as $item){
            $total += $item['quantity'] * $item['price'];
        }
        return $total;
    }

    public function markAsCashup()
    {
        $this->cashup = true;
        $this->save();
    }

    protected function casts(): array
    {
        return [
            'date' => 'datetime:d-m-Y H:i',
        ];
    }
}
