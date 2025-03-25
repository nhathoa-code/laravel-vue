<?php

namespace App\Models\Pos;

use App\Models\Pos\CashRegister;
use App\Relations\CashupHistoryRelation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CashupHistory extends Model
{
    use CashupHistoryRelation;

    public $timestamps = false;
    protected $guarded = [];

    public function insertHistory(CashRegister $cashRegister, array $debitTypes, array $data)
    {
        $cashupHistory = $this->create([
            'from' => $cashRegister->last_cashup,
            'to' => now()->format('Y/m/d H:i'),
            'cash' => $data['cash'],
            'cash_out' => $data['cash_out'],
            'others' => $data['others'],
            'others_out' => $data['others_out'],
            'operator' => Auth::user()->id,
            'cash_register_id' => $cashRegister->id
        ]);
        $cashupHistory->insertSumary($debitTypes);
    }

    public function insertSumary(array $debitTypes)
    {
        foreach($debitTypes as $type => $total){
            CashupSumary::insert([
                'debit_type' => $type,
                'total' => $total,
                'cashup_history_id' => $this->id
            ]);
        }
    }

    protected function casts(): array
    {
        return [
            'from' => 'datetime:d-m-Y H:i',
            'to' => 'datetime:d-m-Y H:i'
        ];
    }
}
