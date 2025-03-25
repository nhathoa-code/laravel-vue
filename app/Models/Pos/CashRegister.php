<?php

namespace App\Models\Pos;

use App\Relations\CashRegisterRelation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CashRegister extends Model
{
    use HasFactory;
    use CashRegisterRelation;

    public $timestamps = false;
    protected $guarded = [];

    public function getById(string $id)
    {
        $cashRegister = self::findOrFail($id);
        $cashRegister->load(['transactions' => function ($query) {
            $query->where('cashup', false);
        },'transactions.details']);
        return $cashRegister;
    }

    public function insertTransaction(
        array $data,
        string $transactionType = 'increase'
    )
    {
        $transaction = Transaction::create([
            'date' => now()->format('Y/m/d H:i'),
            'payment_type' => $data['payment_type'],
            'amount_tendered' => $transactionType === 'increase' ?
                $data['amount_tendered'] : $data['refund'],
            'cash_register_id' => $this->id,
            'transaction_type' => $transactionType,
            'operator' => Auth::user()->id
        ]);
        return $transaction;
    }

    public function getUnCashupTransactions()
    {
        return Transaction::where('cash_register_id',$this->id)
            ->where('cashup',false)
            ->get();
    }

    public function updateLastCashup()
    {
        $this->last_cashup = now()->format('Y/m/d H:i');
        $this->save();
    }

    public function updateCash(int $cash, string $change)
    {
        switch($change){
            case 'increase':
                $this->total_bankable += $cash;
                $this->save();
                break;
            case 'decrease':
                $this->total_bankable -= $cash;
                $this->save();
                break;
            case 'reset':
                $this->total_bankable = $cash;
                $this->save();
                break;
        }
    }

    public function updateTotalIncome(
        int $total, 
        string $change, 
        bool $cash = false
    )
    {
        switch($change){
            case 'increase':
                if($cash) $this->total_income_cash += $total;
                else $this->total_income += $total;
                $this->save();
                break;
            case 'reset':
                if($cash) $this->total_income_cash = $total;
                else $this->total_income_cash = $total;
                $this->save();
                break;
        }
    }

    public function updateOutgoing(
        int $total, 
        string $change, 
        bool $cash = false
    )
    {
        switch($change){
            case 'increase':
                if($cash) $this->total_outgoing_cash += $total;
                else $this->total_outgoing += $total;
                $this->save();
                break;
            case 'reset':
                if($cash) $this->total_outgoing_cash = $total;
                else $this->total_outgoing = $total;
                $this->save();
                break;
        }
    }

    public function cashup()
    {
        $debitTypes = array(); 
        $payments = ['cash'=> 0,'cash_out'=> 0,'others'=> 0,'others_out' => 0];
        $unCashupTransactions = $this->getUnCashupTransactions();
        $unCashupTransactions->each(function($tran) use(&$debitTypes,&$payments,&$total){
            $tran->markAsCashup();
            $tran->details->each(function($item) use($tran,&$debitTypes,&$payments,&$total){
                $total += $item->quantity * $item->price;
                if(isset($debitTypes[$item->debit_type])){
                    if($tran->transaction_type == 'increase'){
                        $debitTypes[$item->debit_type] += $item->quantity * $item->price;
                        if($tran->payment_type == 'CASH'){
                            $payments['cash'] += $item->quantity * $item->price;
                        }else{
                            $payments['others'] += $item->quantity * $item->price;
                        }
                    }else{
                        $debitTypes[$item->debit_type] -= $item->quantity * $item->price;
                        if($tran->payment_type == 'CASH'){
                            $payments['cash_out'] += $item->quantity * $item->price;
                        }else{
                            $payments['others_out'] += $item->quantity * $item->price;
                        }
                    }
                }else{
                    $debitTypes[$item->debit_type] = $item->quantity * $item->price;
                    if($tran->payment_type == 'CASH'){
                        $payments['cash'] += $item->quantity * $item->price;
                    }else{
                        $payments['others'] += $item->quantity * $item->price;
                    }
                }
            });
        });
        $this->updateLastCashup();
        $this->updateCash(0,'reset');
        $this->updateTotalIncome(0,'reset');
        $cashupHistoryModel = new CashupHistory;
        $cashupHistoryModel->insertHistory($this,$debitTypes,$payments);
    }

    protected function casts(): array
    {
        return [
            'last_cashup' => 'datetime:d-m-Y H:i',
        ];
    }
}
